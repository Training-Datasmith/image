<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Contrast_Modifier as GenericContrastModifier;
class Contrast_Modifier extends Generic_Contrast_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            imagefilter($frame->native(), IMG_FILTER_CONTRAST, $this->level * -1);
        }
        return $image;
    }
}