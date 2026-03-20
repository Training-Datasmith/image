<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Hsv;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
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
    public static array $channels = [Channels\Hue::class, Channels\Saturation::class, Channels\Value::class];
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
            Hsl_Color::class => $this->import_hsl_color($color),
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
        // calculate chroma
        $min = min(...$values);
        $max = max(...$values);
        $chroma = $max - $min;
        // calculate value
        $v = 100 * $max;
        if ($chroma == 0) {
            // greyscale color
            return new Color(0, 0, intval(round($v)));
        }
        // calculate saturation
        $s = 100 * ($chroma / $max);
        // calculate hue
        [$r, $g, $b] = $values;
        $h = match (true) {
            $r == $min => 3 - ($g - $b) / $chroma,
            $b == $min => 1 - ($r - $g) / $chroma,
            default => 5 - ($b - $r) / $chroma,
        } * 60;
        return new Color(intval(round($h)), intval(round($s)), intval(round($v)));
    }
    /**
     * @throws ColorException
     */
    protected function import_hsl_color(Color_Interface $color): Color_Interface
    {
        if (!$color instanceof Hsl_Color) {
            throw new Color_Exception('Unabled to import color of type ' . $color::class . '.');
        }
        // normalized values of hsl channels
        [$h, $s, $l] = array_map(fn(Color_Channel_Interface $channel): float => $channel->normalize(), $color->channels());
        $v = $l + $s * min($l, 1 - $l);
        $s = $v == 0 ? 0 : 2 * (1 - $l / $v);
        return $this->color_from_normalized([$h, $s, $v]);
    }
}