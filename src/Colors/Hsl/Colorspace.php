<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Hsl;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Color_Channel_Interface;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
class Colorspace implements Colorspace_Interface
{
    /**
     * Channel class names of colorspace
     *
     * @var array<string>
     */
    public static array $channels = [Channels\Hue::class, Channels\Saturation::class, Channels\Luminance::class];
    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
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
            Cmyk_Color::class => $this->import_rgb_color($color->convert_to(Rgb_Colorspace::class)),
            Rgb_Color::class => $this->import_rgb_color($color),
            Hsv_Color::class => $this->import_hsv_color($color),
            default => $color,
        };
    }
    /**
     * @throws ColorException
     */
    protected function import_rgb_color(Color_Interface $color): Color_Interface
    {
        if (!$color instanceof Rgb_Color) {
            throw new Color_Exception('Unabled to import color of type ' . $color::class . '.');
        }
        // normalized values of rgb channels
        $values = array_map(fn(Color_Channel_Interface $channel): float => $channel->normalize(), $color->channels());
        // take only RGB
        $values = array_slice($values, 0, 3);
        // calculate Luminance
        $min = min(...$values);
        $max = max(...$values);
        $luminance = ($max + $min) / 2;
        $delta = $max - $min;
        // calculate saturation
        $saturation = match (true) {
            $delta == 0 => 0,
            default => $delta / (1 - abs(2 * $luminance - 1)),
        };
        // calculate hue
        [$r, $g, $b] = $values;
        $hue = match (true) {
            $delta == 0 => 0,
            $max == $r => 60 * fmod(($g - $b) / $delta, 6),
            $max == $g => 60 * (($b - $r) / $delta + 2),
            $max == $b => 60 * (($r - $g) / $delta + 4),
            default => 0,
        };
        $hue = ($hue + 360) % 360;
        // normalize hue
        return new Color(intval(round($hue)), intval(round($saturation * 100)), intval(round($luminance * 100)));
    }
    /**
     * @throws ColorException
     */
    protected function import_hsv_color(Color_Interface $color): Color_Interface
    {
        if (!$color instanceof Hsv_Color) {
            throw new Color_Exception('Unabled to import color of type ' . $color::class . '.');
        }
        // normalized values of hsv channels
        [$h, $s, $v] = array_map(fn(Color_Channel_Interface $channel): float => $channel->normalize(), $color->channels());
        // calculate Luminance
        $luminance = (2 - $s) * $v / 2;
        // calculate Saturation
        $saturation = match (true) {
            $luminance == 0 => $s,
            $luminance == 1 => 0,
            $luminance < 0.5 => $s * $v / ($luminance * 2),
            default => $s * $v / (2 - $luminance * 2),
        };
        return new Color(intval(round($h * 360)), intval(round($saturation * 100)), intval(round($luminance * 100)));
    }
}