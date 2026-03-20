<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Data_Uri_Image_Decoder extends Binary_Image_Decoder
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
        $uri = $this->parse_data_uri($input);
        if (!$uri->is_valid()) {
            throw new Decoder_Exception('Unable to decode input');
        }
        if ($uri->is_base64encoded()) {
            return parent::decode(base64_decode($uri->data()));
        }
        return parent::decode(urldecode($uri->data()));
    }
}