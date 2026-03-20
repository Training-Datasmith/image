<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Abstract_Decoder;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class String_Color_Decoder extends Abstract_Decoder implements Decoder_Interface
{
    /**
     * Decode rgb color strings
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_string($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $pattern = '/^s?rgba?\((?P<r>[0-9\.]+%?), ?(?P<g>[0-9\.]+%?), ?(?P<b>[0-9\.]+%?)' . '(?:, ?(?P<a>(?:1)|(?:1\.0*)|(?:0)|(?:0?\.\d+%?)|(?:\d{1,3}%)))?\)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new Decoder_Exception('Unable to decode input');
        }
        // rgb values
        $values = array_map(fn(string $value): int => match (strpos($value, '%')) {
            false => intval(trim($value)),
            default => intval(round(floatval(trim(str_replace('%', '', $value))) / 100 * 255)),
        }, [$matches['r'], $matches['g'], $matches['b']]);
        // alpha value
        if (array_key_exists('a', $matches)) {
            $values[] = match (true) {
                strpos($matches['a'], '%') => round(intval(trim(str_replace('%', '', $matches['a']))) / 2.55),
                default => intval(round(floatval(trim($matches['a'])) * 255)),
            };
        }
        return new Color(...$values);
    }
}