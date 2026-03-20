<?php

declare (strict_types=1);
namespace Intervention\Image\Decoders;

use Intervention\Image\Drivers\Abstract_Decoder;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Image_Object_Decoder extends Abstract_Decoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_a($input, Image_Interface::class)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        return $input;
    }
}