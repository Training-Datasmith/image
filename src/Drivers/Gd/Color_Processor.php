<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Color_Processor_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
class Color_Processor implements Color_Processor_Interface
{
    /**
     * Create new color processor object
     */
    public function __construct(protected Colorspace_Interface $colorspace = new Colorspace())
    {
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorProcessorInterface::colorToNative()
     */
    public function color_to_native(Color_Interface $color): int
    {
        // convert color to colorspace
        $color = $color->convert_to($this->colorspace);
        // gd only supports rgb so the channels can be accessed directly
        $r = $color->channel(Red::class)->value();
        $g = $color->channel(Green::class)->value();
        $b = $color->channel(Blue::class)->value();
        $a = $color->channel(Alpha::class)->value();
        // convert alpha value to gd alpha
        // ([opaque]255-0[transparent]) to ([opaque]0-127[transparent])
        $a = (int) $this->convert_range($a, 0, 255, 127, 0);
        return ($a << 24) + ($r << 16) + ($g << 8) + $b;
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorProcessorInterface::nativeToColor()
     */
    public function native_to_color(mixed $value): Color_Interface
    {
        if (!is_int($value) && !is_array($value)) {
            throw new Color_Exception('GD driver can only decode colors in integer and array format.');
        }
        if (is_array($value)) {
            // array conversion
            if (!$this->is_valid_array_color($value)) {
                throw new Color_Exception('GD driver can only decode array color format array{red: int, green: int, blue: int, alpha: int}.');
            }
            $r = $value['red'];
            $g = $value['green'];
            $b = $value['blue'];
            $a = $value['alpha'];
        } else {
            // integer conversion
            $a = $value >> 24 & 0xff;
            $r = $value >> 16 & 0xff;
            $g = $value >> 8 & 0xff;
            $b = $value & 0xff;
        }
        // convert gd apha integer to intervention alpha integer
        // ([opaque]0-127[transparent]) to ([opaque]255-0[transparent])
        $a = (int) static::convert_range($a, 127, 0, 0, 255);
        return new Color($r, $g, $b, $a);
    }
    /**
     * Convert input in range (min) to (max) to the corresponding value
     * in target range (targetMin) to (targetMax).
     */
    protected function convert_range(float|int $input, float|int $min, float|int $max, float|int $target_min, float|int $target_max): float|int
    {
        return ceil(($input - $min) * ($target_max - $target_min) / ($max - $min) + $target_min);
    }
    /**
     * Check if given array is valid color format
     * array{red: int, green: int, blue: int, alpha: int}
     * i.e. result of imagecolorsforindex()
     *
     * @param array<mixed> $color
     */
    private function is_valid_array_color(array $color): bool
    {
        if (!array_key_exists('red', $color)) {
            return false;
        }
        if (!array_key_exists('green', $color)) {
            return false;
        }
        if (!array_key_exists('blue', $color)) {
            return false;
        }
        if (!array_key_exists('alpha', $color)) {
            return false;
        }
        if (!is_int($color['red'])) {
            return false;
        }
        if (!is_int($color['green'])) {
            return false;
        }
        if (!is_int($color['blue'])) {
            return false;
        }
        if (!is_int($color['alpha'])) {
            return false;
        }
        return true;
    }
}