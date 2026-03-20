<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\Imagick\Modifiers\Strip_Meta_Modifier;
use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Jpeg_Encoder as GenericJpegEncoder;
use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Jpeg_Encoder extends Generic_Jpeg_Encoder implements Specialized_Interface
{
    public function encode(Image_Interface $image): Encoded_Image_Interface
    {
        $format = 'JPEG';
        $compression = Imagick::COMPRESSION_JPEG;
        $blending_color = $this->driver()->handle_input($this->driver()->config()->blending_color);
        // resolve blending color because jpeg has no transparency
        $background = $this->driver()->color_processor($image->colorspace())->color_to_native($blending_color);
        // set alpha value to 1 because Imagick renders
        // possible full transparent colors as black
        $background->set_color_value(Imagick::COLOR_ALPHA, 1);
        // strip meta data
        if ($this->strip || is_null($this->strip) && $this->driver()->config()->strip) {
            $image->modify(new Strip_Meta_Modifier());
        }
        /** @var Imagick $imagick */
        $imagick = $image->core()->native();
        $imagick->set_image_background_color($background);
        $imagick->set_background_color($background);
        $imagick->set_format($format);
        $imagick->set_image_format($format);
        $imagick->set_compression($compression);
        $imagick->set_image_compression($compression);
        $imagick->set_compression_quality($this->quality);
        $imagick->set_image_compression_quality($this->quality);
        $imagick->set_image_alpha_channel(Imagick::ALPHACHANNEL_REMOVE);
        if ($this->progressive) {
            $imagick->set_interlace_scheme(Imagick::INTERLACE_PLANE);
        }
        return new Encoded_Image($imagick->get_images_blob(), 'image/jpeg');
    }
}