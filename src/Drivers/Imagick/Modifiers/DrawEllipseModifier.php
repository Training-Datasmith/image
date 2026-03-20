<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick_Draw;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Ellipse_Modifier as GenericDrawEllipseModifier;
use RuntimeException;
class Draw_Ellipse_Modifier extends Generic_Draw_Ellipse_Modifier implements Specialized_Interface
{
    /**
     * @throws RuntimeException
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $background_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color());
        $border_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->border_color());
        foreach ($image as $frame) {
            $drawing = new Imagick_Draw();
            $drawing->set_fill_color($background_color);
            if ($this->drawable->has_border()) {
                $drawing->set_stroke_width($this->drawable->border_size());
                $drawing->set_stroke_color($border_color);
            }
            $drawing->ellipse($this->drawable->position()->x(), $this->drawable->position()->y(), $this->drawable->width() / 2, $this->drawable->height() / 2, 0, 360);
            $frame->native()->draw_image($drawing);
        }
        return $image;
    }
}