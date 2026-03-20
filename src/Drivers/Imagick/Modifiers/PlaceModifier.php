<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Place_Modifier as GenericPlaceModifier;
class Place_Modifier extends Generic_Place_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $watermark = $this->driver()->handle_input($this->element);
        $position = $this->get_position($image, $watermark);
        // set opacity of watermark
        if ($this->opacity < 100) {
            $watermark->core()->native()->set_image_alpha_channel(Imagick::ALPHACHANNEL_SET);
            $watermark->core()->native()->evaluate_image(Imagick::EVALUATE_DIVIDE, $this->opacity > 0 ? 100 / $this->opacity : 1000, Imagick::CHANNEL_ALPHA);
        }
        foreach ($image as $frame) {
            $frame->native()->composite_image($watermark->core()->native(), Imagick::COMPOSITE_DEFAULT, $position->x(), $position->y());
        }
        return $image;
    }
}