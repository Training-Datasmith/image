<?php

declare (strict_types=1);
namespace Intervention\Image\Encoders;

use Error;
use Intervention\Image\Drivers\Abstract_Encoder;
use Intervention\Image\Exceptions\Encoder_Exception;
use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Encoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Media_Type;
class Media_Type_Encoder extends Abstract_Encoder
{
    /**
     * Encoder options
     *
     * @var array<string, mixed>
     */
    protected array $options = [];
    /**
     * Create new encoder instance
     *
     * @param null|string|MediaType $mediaType Target media type for example "image/jpeg"
     */
    public function __construct(public null|string|Media_Type $media_type = null, mixed ...$options)
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
        $media_type = is_null($this->media_type) ? $image->origin()->media_type() : $this->media_type;
        return $image->encode($this->encoder_by_media_type($media_type));
    }
    /**
     * Return new encoder by given media (MIME) type
     *
     * @throws EncoderException
     */
    protected function encoder_by_media_type(string|Media_Type $media_type): Encoder_Interface
    {
        try {
            $media_type = is_string($media_type) ? Media_Type::from($media_type) : $media_type;
        } catch (Error) {
            throw new Encoder_Exception('No encoder found for media type (' . $media_type . ').');
        }
        return $media_type->format()->encoder(...$this->options);
    }
}