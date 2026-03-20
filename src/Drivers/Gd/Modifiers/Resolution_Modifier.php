<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Resolution_Modifier as GenericResolutionModifier;
class Resolution_Modifier extends Generic_Resolution_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $x = intval(round($this->x));
        $y = intval(round($this->y));
        foreach ($image as $frame) {
            imageresolution($frame->native(), $x, $y);
        }
        return $image;
    }
}