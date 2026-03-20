<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Pixelate_Modifier as GenericPixelateModifier;
class Pixelate_Modifier extends Generic_Pixelate_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            $this->pixelate_frame($frame);
        }
        return $image;
    }
    protected function pixelate_frame(Frame_Interface $frame): void
    {
        $size = $frame->size();
        $frame->native()->scale_image((int) round(max(1, $size->width() / $this->size)), (int) round(max(1, $size->height() / $this->size)));
        $frame->native()->scale_image($size->width(), $size->height());
    }
}