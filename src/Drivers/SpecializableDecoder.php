<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specializable_Interface;
use Intervention\Image\Traits\Can_Be_Driver_Specialized;
abstract class Specializable_Decoder extends Abstract_Decoder implements Specializable_Interface
{
    use Can_Be_Driver_Specialized;
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        throw new Decoder_Exception('Decoder must be specialized by the driver first.');
    }
}