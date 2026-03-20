<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Gamma_Modifier as GenericGammaModifier;
class Gamma_Modifier extends Generic_Gamma_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            imagegammacorrect($frame->native(), 1, $this->gamma);
        }
        return $image;
    }
}