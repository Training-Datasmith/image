<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick_Draw;
use Imagick_Pixel;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Contain_Modifier as GenericContainModifier;
class Contain_Modifier extends Generic_Contain_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $crop = $this->get_crop_size($image);
        $resize = $this->get_resize_size($image);
        $transparent = new Imagick_Pixel('transparent');
        $background = $this->driver()->color_processor($image->colorspace())->color_to_native($this->driver()->handle_input($this->background));
        foreach ($image as $frame) {
            $frame->native()->scale_image($crop->width(), $crop->height());
            $frame->native()->set_background_color($transparent);
            $frame->native()->set_image_background_color($transparent);
            $frame->native()->extent_image($resize->width(), $resize->height(), $crop->pivot()->x() * -1, $crop->pivot()->y() * -1);
            if ($resize->width() > $crop->width()) {
                // fill new emerged background
                $draw = new Imagick_Draw();
                $draw->set_fill_color($background);
                $delta = abs($crop->pivot()->x());
                if ($delta > 0) {
                    $draw->rectangle(0, 0, $delta - 1, $resize->height());
                }
                $draw->rectangle($crop->width() + $delta, 0, $resize->width(), $resize->height());
                $frame->native()->draw_image($draw);
            }
            if ($resize->height() > $crop->height()) {
                // fill new emerged background
                $draw = new Imagick_Draw();
                $draw->set_fill_color($background);
                $delta = abs($crop->pivot()->y());
                if ($delta > 0) {
                    $draw->rectangle(0, 0, $resize->width(), $delta - 1);
                }
                $draw->rectangle(0, $crop->height() + $delta, $resize->width(), $resize->height());
                $frame->native()->draw_image($draw);
            }
        }
        return $image;
    }
}