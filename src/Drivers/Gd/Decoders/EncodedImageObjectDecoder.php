<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Encoded_Image;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Encoded_Image_Object_Decoder extends Binary_Image_Decoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_a($input, Encoded_Image::class)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        return parent::decode($input->to_string());
    }
}