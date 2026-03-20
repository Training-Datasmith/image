<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick_Draw;
use Imagick_Pixel;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Polygon_Modifier as GenericDrawPolygonModifier;
use RuntimeException;
class Draw_Polygon_Modifier extends Generic_Draw_Polygon_Modifier implements Specialized_Interface
{
    /**
     * @throws RuntimeException
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $drawing = new Imagick_Draw();
        $drawing->set_fill_color(new Imagick_Pixel('transparent'));
        // defaults to no backgroundColor
        if ($this->drawable->has_background_color()) {
            $background_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color());
            $drawing->set_fill_color($background_color);
        }
        if ($this->drawable->has_border()) {
            $border_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->border_color());
            $drawing->set_stroke_color($border_color);
            $drawing->set_stroke_width($this->drawable->border_size());
        }
        $drawing->polygon($this->points());
        foreach ($image as $frame) {
            $frame->native()->draw_image($drawing);
        }
        return $image;
    }
    /**
     * Return points of drawable in processable form for ImagickDraw
     *
     * @return array<array<string, int>>
     */
    private function points(): array
    {
        $points = [];
        foreach ($this->drawable as $point) {
            $points[] = ['x' => $point->x(), 'y' => $point->y()];
        }
        return $points;
    }
}