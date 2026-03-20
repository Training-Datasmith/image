<?php

declare (strict_types=1);
namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Auto_Encoder extends Media_Type_Encoder
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image_Interface
    {
        return $image->encode($this->encoder_by_media_type($image->origin()->media_type()));
    }
}