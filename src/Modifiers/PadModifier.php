<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Pad_Modifier extends Contain_Modifier
{
    public function get_crop_size(Image_Interface $image): Size_Interface
    {
        return $image->size()->contain_max($this->width, $this->height)->align_pivot_to($this->get_resize_size($image), $this->position);
    }
}