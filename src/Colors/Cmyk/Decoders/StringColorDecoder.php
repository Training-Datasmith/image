<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Cmyk\Decoders;

use Intervention\Image\Colors\Cmyk\Color;
use Intervention\Image\Drivers\Abstract_Decoder;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class String_Color_Decoder extends Abstract_Decoder implements Decoder_Interface
{
    /**
     * Decode CMYK color strings
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_string($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $pattern = '/^cmyk\((?P<c>[0-9\.]+%?), ?(?P<m>[0-9\.]+%?), ?(?P<y>[0-9\.]+%?), ?(?P<k>[0-9\.]+%?)\)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $values = array_map(fn(string $value): int => intval(round(floatval(trim(str_replace('%', '', $value))))), [$matches['c'], $matches['m'], $matches['y'], $matches['k']]);
        return new Color(...$values);
    }
}