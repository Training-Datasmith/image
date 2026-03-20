<?php

declare (strict_types=1);
namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class File_Path_Encoder extends File_Extension_Encoder
{
    /**
     * Create new encoder instance to encode to format of file extension in given path
     */
    public function __construct(protected ?string $path = null, mixed ...$options)
    {
        parent::__construct(is_null($path) ? $path : pathinfo($path, PATHINFO_EXTENSION), ...$options);
    }
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image_Interface
    {
        return $image->encode($this->encoder_by_file_extension(is_null($this->path) ? $image->origin()->file_extension() : pathinfo($this->path, PATHINFO_EXTENSION)));
    }
}