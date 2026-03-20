<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Fill_Modifier as GenericFillModifier;
class Fill_Modifier extends Generic_Fill_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $color = $this->color($image);
        foreach ($image as $frame) {
            if ($this->has_position()) {
                $this->flood_fill_with_color($frame, $color);
            } else {
                $this->fill_all_with_color($frame, $color);
            }
        }
        return $image;
    }
    /**
     * @throws RuntimeException
     */
    private function color(Image_Interface $image): int
    {
        return $this->driver()->color_processor($image->colorspace())->color_to_native($this->driver()->handle_input($this->color));
    }
    private function flood_fill_with_color(Frame_Interface $frame, int $color): void
    {
        imagefill($frame->native(), $this->position->x(), $this->position->y(), $color);
    }
    private function fill_all_with_color(Frame_Interface $frame, int $color): void
    {
        imagealphablending($frame->native(), true);
        imagefilledrectangle($frame->native(), 0, 0, $frame->size()->width() - 1, $frame->size()->height() - 1, $color);
    }
}