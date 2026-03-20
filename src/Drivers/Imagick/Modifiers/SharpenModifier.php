<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Sharpen_Modifier as GenericSharpenModifier;
class Sharpen_Modifier extends Generic_Sharpen_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            $frame->native()->unsharp_mask_image(1, 1, $this->amount / 6.25, 0);
        }
        return $image;
    }
}