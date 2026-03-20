<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Rotate_Modifier as GenericRotateModifier;
class Rotate_Modifier extends Generic_Rotate_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $background = $this->driver()->color_processor($image->colorspace())->color_to_native($this->driver()->handle_input($this->background));
        foreach ($image as $frame) {
            $frame->native()->rotate_image($background, $this->rotation_angle() * -1);
        }
        return $image;
    }
}