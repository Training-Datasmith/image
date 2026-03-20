<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Hsv\Decoders;

use Intervention\Image\Colors\Hsv\Color;
use Intervention\Image\Drivers\Abstract_Decoder;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class String_Color_Decoder extends Abstract_Decoder implements Decoder_Interface
{
    /**
     * Decode hsv/hsb color strings
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_string($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $pattern = '/^hs(v|b)\((?P<h>[0-9\.]+), ?(?P<s>[0-9\.]+%?), ?(?P<v>[0-9\.]+%?)\)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $values = array_map(fn(string $value): int => match (strpos($value, '%')) {
            false => intval(trim($value)),
            default => intval(trim(str_replace('%', '', $value))),
        }, [$matches['h'], $matches['s'], $matches['v']]);
        return new Color(...$values);
    }
}