<?php

declare (strict_types=1);
namespace Intervention\Image;

use Intervention\Image\Interfaces\Encoded_Image_Interface;
class Encoded_Image extends File implements Encoded_Image_Interface
{
    /**
     * Create new instance
     *
     * @param string|resource $data
     */
    public function __construct(mixed $data, protected string $media_type = 'application/octet-stream')
    {
        parent::__construct($data);
    }
    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::mediaType()
     */
    public function media_type(): string
    {
        return $this->media_type;
    }
    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::mimetype()
     */
    public function mimetype(): string
    {
        return $this->media_type();
    }
    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::toDataUri()
     */
    public function to_data_uri(): string
    {
        return sprintf('data:%s;base64,%s', $this->media_type(), base64_encode((string) $this));
    }
    /**
     * Show debug info for the current image
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return ['mediaType' => $this->media_type(), 'size' => $this->size()];
    }
}