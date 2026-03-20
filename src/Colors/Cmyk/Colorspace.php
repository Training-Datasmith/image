<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Cmyk;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
class Colorspace implements Colorspace_Interface
{
    /**
     * Channel class names of colorspace
     *
     * @var array<string>
     */
    public static array $channels = [Channels\Cyan::class, Channels\Magenta::class, Channels\Yellow::class, Channels\Key::class];
    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::createColor()
     */
    public function color_from_normalized(array $normalized): Color_Interface
    {
        return new Color(...array_map(fn(string $classname, float $value_normalized) => (new $classname(normalized: $value_normalized))->value(), self::$channels, $normalized));
    }
    /**
     * @throws ColorException
     */
    public function import_color(Color_Interface $color): Color_Interface
    {
        return match ($color::class) {
            Rgb_Color::class => $this->import_rgb_color($color),
            Hsv_Color::class => $this->import_rgb_color($color->convert_to(Rgb_Colorspace::class)),
            Hsl_Color::class => $this->import_rgb_color($color->convert_to(Rgb_Colorspace::class)),
            default => $color,
        };
    }
    /**
     * @throws ColorException
     */
    protected function import_rgb_color(Color_Interface $color): Cmyk_Color
    {
        if (!$color instanceof Rgb_Color) {
            throw new Color_Exception('Unabled to import color of type ' . $color::class . '.');
        }
        $c = (255 - $color->red()->value()) / 255.0 * 100;
        $m = (255 - $color->green()->value()) / 255.0 * 100;
        $y = (255 - $color->blue()->value()) / 255.0 * 100;
        $k = intval(round(min([$c, $m, $y])));
        $c = intval(round($c - $k));
        $m = intval(round($m - $k));
        $y = intval(round($y - $k));
        return new Cmyk_Color($c, $m, $y, $k);
    }
}