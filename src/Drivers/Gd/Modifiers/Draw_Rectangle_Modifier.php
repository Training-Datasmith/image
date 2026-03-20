<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Rectangle_Modifier as GenericDrawRectangleModifier;
use RuntimeException;
class Draw_Rectangle_Modifier extends Generic_Draw_Rectangle_Modifier implements Specialized_Interface
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
        $position = $this->drawable->position();
        foreach ($image as $frame) {
            // draw background
            if ($this->drawable->has_background_color()) {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), 0);
                imagefilledrectangle($frame->native(), $position->x(), $position->y(), $position->x() + $this->drawable->width(), $position->y() + $this->drawable->height(), $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color()));
            }
            // draw border
            if ($this->drawable->has_border()) {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), $this->drawable->border_size());
                imagerectangle($frame->native(), $position->x(), $position->y(), $position->x() + $this->drawable->width(), $position->y() + $this->drawable->height(), $this->driver()->color_processor($image->colorspace())->color_to_native($this->border_color()));
            }
        }
        return $image;
    }
}