<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use Imagick_Pixel;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Color_Processor_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
class Color_Processor implements Color_Processor_Interface
{
    public function __construct(protected Colorspace_Interface $colorspace)
    {
    }
    public function color_to_native(Color_Interface $color): Imagick_Pixel
    {
        return new Imagick_Pixel((string) $color->convert_to($this->colorspace));
    }
    public function native_to_color(mixed $native): Color_Interface
    {
        return match ($this->colorspace::class) {
            Cmyk_Colorspace::class => $this->colorspace->color_from_normalized([$native->get_color_value(Imagick::COLOR_CYAN), $native->get_color_value(Imagick::COLOR_MAGENTA), $native->get_color_value(Imagick::COLOR_YELLOW), $native->get_color_value(Imagick::COLOR_BLACK)]),
            default => $this->colorspace->color_from_normalized([$native->get_color_value(Imagick::COLOR_RED), $native->get_color_value(Imagick::COLOR_GREEN), $native->get_color_value(Imagick::COLOR_BLUE), $native->get_color_value(Imagick::COLOR_ALPHA)]),
        };
    }
}