<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Flop_Modifier as ModifiersFlopModifier;
class Flop_Modifier extends Modifiers_Flop_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            $frame->native()->flop_image();
        }
        return $image;
    }
}