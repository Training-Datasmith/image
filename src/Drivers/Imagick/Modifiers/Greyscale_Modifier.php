<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Greyscale_Modifier as GenericGreyscaleModifier;
class Greyscale_Modifier extends Generic_Greyscale_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            $frame->native()->modulate_image(100, 0, 100);
        }
        return $image;
    }
}