<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Flip_Modifier as GenericFlipModifier;
class Flip_Modifier extends Generic_Flip_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            imageflip($frame->native(), IMG_FLIP_VERTICAL);
        }
        return $image;
    }
}