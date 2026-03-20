<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Contrast_Modifier as GenericContrastModifier;
class Contrast_Modifier extends Generic_Contrast_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            $frame->native()->sigmoidal_contrast_image($this->level > 0, abs($this->level / 4), 0);
        }
        return $image;
    }
}