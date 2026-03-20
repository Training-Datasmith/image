<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick_Draw;
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
        $drawing = new Imagick_Draw();
        $drawing->set_stroke_width($this->drawable->width());
        $drawing->set_fill_opacity(0);
        $drawing->set_stroke_color($this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color()));
        $drawing->line($this->drawable->start()->x(), $this->drawable->start()->y(), $this->drawable->end()->x(), $this->drawable->end()->y());
        foreach ($image as $frame) {
            $frame->native()->draw_image($drawing);
        }
        return $image;
    }
}