<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick_Draw;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Pixel_Modifier as GenericDrawPixelModifier;
class Draw_Pixel_Modifier extends Generic_Draw_Pixel_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->driver()->handle_input($this->color));
        $pixel = new Imagick_Draw();
        $pixel->set_fill_color($color);
        $pixel->point($this->position->x(), $this->position->y());
        foreach ($image as $frame) {
            $frame->native()->draw_image($pixel);
        }
        return $image;
    }
}