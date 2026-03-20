<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Scale_Down_Modifier extends Resize_Modifier
{
    /**
     * {@inheritdoc}
     *
     * @see ResizeModifier::getAdjustedSize()
     */
    protected function get_adjusted_size(Image_Interface $image): Size_Interface
    {
        return $image->size()->scale_down($this->width, $this->height);
    }
}