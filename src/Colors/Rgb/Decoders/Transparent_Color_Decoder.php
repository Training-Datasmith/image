<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Transparent_Color_Decoder extends Hex_Color_Decoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_string($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        if (strtolower($input) !== 'transparent') {
            throw new Decoder_Exception('Unable to decode input');
        }
        return parent::decode('#ffffff00');
    }
}