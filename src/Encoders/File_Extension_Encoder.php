<?php

declare (strict_types=1);
namespace Intervention\Image\Encoders;

use Error;
use Intervention\Image\Exceptions\Encoder_Exception;
use Intervention\Image\File_Extension;
use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Encoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class File_Extension_Encoder extends Auto_Encoder
{
    /**
     * Encoder options
     *
     * @var array<string, mixed>
     */
    protected array $options = [];
    /**
     * Create new encoder instance to encode to format of given file extension
     *
     * @param null|string|FileExtension $extension Target file extension for example "png"
     */
    public function __construct(public null|string|File_Extension $extension = null, mixed ...$options)
    {
        $this->options = $options;
    }
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image_Interface
    {
        $extension = is_null($this->extension) ? $image->origin()->file_extension() : $this->extension;
        return $image->encode($this->encoder_by_file_extension($extension));
    }
    /**
     * Create matching encoder for given file extension
     *
     * @throws EncoderException
     */
    protected function encoder_by_file_extension(null|string|File_Extension $extension): Encoder_Interface
    {
        if (empty($extension)) {
            throw new Encoder_Exception('No encoder found for empty file extension.');
        }
        try {
            $extension = is_string($extension) ? File_Extension::from(strtolower($extension)) : $extension;
        } catch (Error) {
            throw new Encoder_Exception('No encoder found for file extension (' . $extension . ').');
        }
        return $extension->format()->encoder(...$this->options);
    }
}