<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Hsl\Decoders;

use Intervention\Image\Colors\Hsl\Color;
use Intervention\Image\Drivers\Abstract_Decoder;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class String_Color_Decoder extends Abstract_Decoder implements Decoder_Interface
{
    /**
     * Decode hsl color strings
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_string($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $pattern = '/^hsl\((?P<h>[0-9\.]+), ?(?P<s>[0-9\.]+%?), ?(?P<l>[0-9\.]+%?)\)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $values = array_map(fn(string $value): int => match (strpos($value, '%')) {
            false => intval(trim($value)),
            default => intval(trim(str_replace('%', '', $value))),
        }, [$matches['h'], $matches['s'], $matches['l']]);
        return new Color(...$values);
    }
}