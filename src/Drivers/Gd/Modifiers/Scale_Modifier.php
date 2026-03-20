<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Scale_Modifier extends Resize_Modifier
{
    protected function get_adjusted_size(Image_Interface $image): Size_Interface
    {
        return $image->size()->scale($this->width, $this->height);
    }
}