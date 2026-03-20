<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Resize_Modifier as GenericResizeModifier;
class Resize_Modifier extends Generic_Resize_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $resize_to = $this->get_adjusted_size($image);
        foreach ($image as $frame) {
            $frame->native()->scale_image($resize_to->width(), $resize_to->height());
        }
        return $image;
    }
    /**
     * @throws RuntimeException
     */
    protected function get_adjusted_size(Image_Interface $image): Size_Interface
    {
        return $image->size()->resize($this->width, $this->height);
    }
}