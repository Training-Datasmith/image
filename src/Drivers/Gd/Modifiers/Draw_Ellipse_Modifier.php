<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Ellipse_Modifier as GenericDrawEllipseModifier;
use RuntimeException;
class Draw_Ellipse_Modifier extends Generic_Draw_Ellipse_Modifier implements Specialized_Interface
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
            if ($this->drawable->has_border()) {
                imagealphablending($frame->native(), true);
                // slightly smaller ellipse to keep 1px bordered edges clean
                if ($this->drawable->has_background_color()) {
                    imagefilledellipse($frame->native(), $this->drawable()->position()->x(), $this->drawable->position()->y(), $this->drawable->width() - 1, $this->drawable->height() - 1, $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color()));
                }
                // gd's imageellipse ignores imagesetthickness
                // so i use imagearc with 360 degrees instead.
                imagesetthickness($frame->native(), $this->drawable->border_size());
                imagearc($frame->native(), $this->drawable()->position()->x(), $this->drawable()->position()->y(), $this->drawable->width(), $this->drawable->height(), 0, 360, $this->driver()->color_processor($image->colorspace())->color_to_native($this->border_color()));
            } else {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), 0);
                imagefilledellipse($frame->native(), $this->drawable()->position()->x(), $this->drawable()->position()->y(), $this->drawable->width(), $this->drawable->height(), $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color()));
            }
        }
        return $image;
    }
}