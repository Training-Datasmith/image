<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Abstract_Decoder;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Hex_Color_Decoder extends Abstract_Decoder implements Decoder_Interface
{
    /**
     * Decode hexadecimal rgb colors with and without transparency
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_string($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $pattern = '/^#?(?P<hex>[a-f\d]{3}(?:[a-f\d]?|(?:[a-f\d]{3}(?:[a-f\d]{2})?)?)\b)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $values = match (strlen($matches['hex'])) {
            3, 4 => str_split($matches['hex']),
            6, 8 => str_split($matches['hex'], 2),
            default => throw new Decoder_Exception('Unable to decode input'),
        };
        $values = array_map(fn(string $value): float|int => match (strlen($value)) {
            1 => hexdec($value . $value),
            2 => hexdec($value),
            default => throw new Decoder_Exception('Unable to decode input'),
        }, $values);
        return new Color(...$values);
    }
}