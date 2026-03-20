<?php

declare (strict_types=1);
namespace Intervention\Image;

class Origin
{
    /**
     * Create new origin instance
     */
    public function __construct(protected string $media_type = 'application/octet-stream', protected ?string $file_path = null)
    {
    }
    /**
     * Return media type of origin
     */
    public function media_type(): string
    {
        return $this->media_type;
    }
    /**
     * Alias of self::mediaType()
     */
    public function mimetype(): string
    {
        return $this->media_type();
    }
    /**
     * Set media type of current instance
     */
    public function set_media_type(string|Media_Type $type): self
    {
        $this->media_type = match (true) {
            is_string($type) => $type,
            default => $type->value,
        };
        return $this;
    }
    /**
     * Return file path of origin
     */
    public function file_path(): ?string
    {
        return $this->file_path;
    }
    /**
     * Set file path for origin
     */
    public function set_file_path(string $path): self
    {
        $this->file_path = $path;
        return $this;
    }
    /**
     * Return file extension if origin was created from file path
     */
    public function file_extension(): ?string
    {
        return pathinfo($this->file_path ?: '', PATHINFO_EXTENSION) ?: null;
    }
    /**
     * Show debug info for the current image
     *
     * @return array<string, null|string>
     */
    public function __debugInfo(): array
    {
        return ['mediaType' => $this->media_type(), 'filePath' => $this->file_path()];
    }
}