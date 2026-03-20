<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;
interface Encoder_Interface
{
    /**
     * Encode given image
     *
     * @throws RuntimeException
     */
    public function encode(Image_Interface $image): Encoded_Image_Interface;
}