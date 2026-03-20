<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers;

use Intervention\Image\Encoded_Image;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Encoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Traits\Can_Build_File_Pointer;
abstract class Abstract_Encoder implements Encoder_Interface
{
    use Can_Build_File_Pointer;
    public const DEFAULT_QUALITY = 75;
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image_Interface
    {
        return $image->encode($this);
    }
    /**
     * Build new file pointer, run callback with it and return result as encoded image
     *
     * @throws RuntimeException
     */
    protected function create_encoded_image(callable $callback, ?string $media_type = null): Encoded_Image
    {
        $pointer = $this->build_file_pointer();
        $callback($pointer);
        return is_string($media_type) ? new Encoded_Image($pointer, $media_type) : new Encoded_Image($pointer);
    }
}