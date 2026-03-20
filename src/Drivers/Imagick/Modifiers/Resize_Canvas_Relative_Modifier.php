<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Resize_Canvas_Relative_Modifier extends Resize_Canvas_Modifier
{
    protected function crop_size(Image_Interface $image, bool $relative = false): Size_Interface
    {
        return parent::crop_size($image, true);
    }
}