<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick_Draw;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Rectangle_Modifier as GenericDrawRectangleModifier;
use RuntimeException;
class Draw_Rectangle_Modifier extends Generic_Draw_Rectangle_Modifier implements Specialized_Interface
{
    /**
     * @throws RuntimeException
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $drawing = new Imagick_Draw();
        $background_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color());
        $border_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->border_color());
        $drawing->set_fill_color($background_color);
        if ($this->drawable->has_border()) {
            $drawing->set_stroke_color($border_color);
            $drawing->set_stroke_width($this->drawable->border_size());
        }
        // build rectangle
        $drawing->rectangle($this->drawable->position()->x(), $this->drawable->position()->y(), $this->drawable->position()->x() + $this->drawable->width(), $this->drawable->position()->y() + $this->drawable->height());
        foreach ($image as $frame) {
            $frame->native()->draw_image($drawing);
        }
        return $image;
    }
}