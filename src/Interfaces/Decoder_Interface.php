<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;
interface Decoder_Interface
{
    /**
     * Decode given input either to color or image
     *
     * @throws RuntimeException
     */
    public function decode(mixed $input): Image_Interface|Color_Interface;
}