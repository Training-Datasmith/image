<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick_Draw;
use Intervention\Image\Exceptions\Geometry_Exception;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Bezier_Modifier as GenericDrawBezierModifier;
use RuntimeException;
class Draw_Bezier_Modifier extends Generic_Draw_Bezier_Modifier implements Specialized_Interface
{
    /**
     * @throws RuntimeException
     * @throws GeometryException
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
            throw new Geometry_Exception('You must specify either 3 or 4 points to create a bezier curve');
        }
        $drawing = new Imagick_Draw();
        if ($this->drawable->has_background_color()) {
            $background_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color());
        } else {
            $background_color = 'transparent';
        }
        $drawing->set_fill_color($background_color);
        if ($this->drawable->has_border() && $this->drawable->border_size() > 0) {
            $border_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->border_color());
            $drawing->set_stroke_color($border_color);
            $drawing->set_stroke_width($this->drawable->border_size());
        }
        $drawing->path_start();
        $drawing->path_move_to_absolute($this->drawable->first()->x(), $this->drawable->first()->y());
        if ($this->drawable->count() === 3) {
            $drawing->path_curve_to_quadratic_bezier_absolute($this->drawable->second()->x(), $this->drawable->second()->y(), $this->drawable->last()->x(), $this->drawable->last()->y());
        } elseif ($this->drawable->count() === 4) {
            $drawing->path_curve_to_absolute($this->drawable->second()->x(), $this->drawable->second()->y(), $this->drawable->third()->x(), $this->drawable->third()->y(), $this->drawable->last()->x(), $this->drawable->last()->y());
        }
        $drawing->path_finish();
        foreach ($image as $frame) {
            $frame->native()->draw_image($drawing);
        }
        return $image;
    }
}