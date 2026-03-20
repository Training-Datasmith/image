<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Polygon_Modifier as ModifiersDrawPolygonModifier;
use RuntimeException;
class Draw_Polygon_Modifier extends Modifiers_Draw_Polygon_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws RuntimeException
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            if ($this->drawable->has_background_color()) {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), 0);
                imagefilledpolygon($frame->native(), $this->drawable->to_array(), $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color()));
            }
            if ($this->drawable->has_border()) {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), $this->drawable->border_size());
                imagepolygon($frame->native(), $this->drawable->to_array(), $this->driver()->color_processor($image->colorspace())->color_to_native($this->border_color()));
            }
        }
        return $image;
    }
}