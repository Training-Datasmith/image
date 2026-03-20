<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Blur_Modifier as GenericBlurModifier;
class Blur_Modifier extends Generic_Blur_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            $frame->native()->blur_image($this->amount, 0.5 * $this->amount);
        }
        return $image;
    }
}