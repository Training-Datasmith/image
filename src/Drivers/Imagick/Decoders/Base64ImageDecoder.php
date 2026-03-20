<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Base64image_Decoder extends Binary_Image_Decoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!$this->is_valid_base64($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        return parent::decode(base64_decode((string) $input));
    }
}