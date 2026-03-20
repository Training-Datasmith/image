<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Imagick_Pixel;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Crop_Modifier as GenericCropModifier;
class Crop_Modifier extends Generic_Crop_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        // decode background color
        $background = $this->driver()->color_processor($image->colorspace())->color_to_native($this->driver()->handle_input($this->background));
        // create empty container imagick to rebuild core
        $imagick = new Imagick();
        // save resolution to add it later
        $resolution = $image->resolution()->per_inch();
        // define position of the image on the new canvas
        $crop = $this->crop($image);
        $position = [($crop->pivot()->x() + $this->offset_x) * -1, ($crop->pivot()->y() + $this->offset_y) * -1];
        foreach ($image as $frame) {
            // create new frame canvas with modifiers background
            $canvas = new Imagick();
            $canvas->new_image($crop->width(), $crop->height(), $background, 'png');
            $canvas->set_image_resolution($resolution->x(), $resolution->y());
            $canvas->set_image_alpha_channel(Imagick::ALPHACHANNEL_SET);
            // or ALPHACHANNEL_ACTIVATE?
            // set animation details
            if ($image->is_animated()) {
                $canvas->set_image_delay($frame->native()->get_image_delay());
                $canvas->set_image_iterations($frame->native()->get_image_iterations());
                $canvas->set_image_dispose($frame->native()->get_image_dispose());
            }
            // make the rectangular position of the original image transparent
            // so that we can later place the original on top. this preserves
            // the transparency of the original and shows the background color
            // of the modifier in the other areas. if the original image has no
            // transparent area the rectangular transparency will be covered by
            // the original.
            $clearer = new Imagick();
            $clearer->new_image($frame->native()->get_image_width(), $frame->native()->get_image_height(), new Imagick_Pixel('black'));
            $canvas->composite_image($clearer, Imagick::COMPOSITE_DSTOUT, ...$position);
            // place original frame content onto prepared frame canvas
            $canvas->composite_image($frame->native(), Imagick::COMPOSITE_DEFAULT, ...$position);
            // add newly built frame to container imagick
            $imagick->add_image($canvas);
        }
        // replace imagick in the original image
        $image->core()->set_native($imagick);
        return $image;
    }
}