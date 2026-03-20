<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Line_Modifier as GenericDrawLineModifier;
use RuntimeException;
class Draw_Line_Modifier extends Generic_Draw_Line_Modifier implements Specialized_Interface
{
    /**
     * @throws RuntimeException
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color());
        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imageantialias($frame->native(), true);
            imagesetthickness($frame->native(), $this->drawable->width());
            imageline($frame->native(), $this->drawable->start()->x(), $this->drawable->start()->y(), $this->drawable->end()->x(), $this->drawable->end()->y(), $color);
        }
        return $image;
    }
}