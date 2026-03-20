<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Imagick_Draw;
use Imagick_Pixel;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Fill_Modifier as ModifiersFillModifier;
class Fill_Modifier extends Modifiers_Fill_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $pixel = $this->driver()->color_processor($image->colorspace())->color_to_native($this->driver()->handle_input($this->color));
        foreach ($image->core()->native() as $frame) {
            if ($this->has_position()) {
                $this->flood_fill_with_color($frame, $pixel);
            } else {
                $this->fill_all_with_color($frame, $pixel);
            }
        }
        return $image;
    }
    private function flood_fill_with_color(Imagick $frame, Imagick_Pixel $pixel): void
    {
        $target = $frame->get_image_pixel_color($this->position->x(), $this->position->y());
        $frame->floodfill_paint_image($pixel, 100, $target, $this->position->x(), $this->position->y(), false, Imagick::CHANNEL_ALL);
    }
    private function fill_all_with_color(Imagick $frame, Imagick_Pixel $pixel): void
    {
        $draw = new Imagick_Draw();
        $draw->set_fill_color($pixel);
        $draw->rectangle(0, 0, $frame->get_image_width(), $frame->get_image_height());
        $frame->draw_image($draw);
        // deactive alpha channel when image was filled with opaque color
        if ($pixel->get_color_value(Imagick::COLOR_ALPHA) == 1) {
            $frame->set_image_alpha_channel(Imagick::ALPHACHANNEL_DEACTIVATE);
        }
    }
}