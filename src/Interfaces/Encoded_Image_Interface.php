<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

interface Encoded_Image_Interface extends File_Interface
{
    /**
     * Return Media (MIME) Type of encoded image
     */
    public function media_type(): string;
    /**
     * Alias of self::mediaType()
     */
    public function mimetype(): string;
    /**
     * Transform encoded image data into an data uri string
     */
    public function to_data_uri(): string;
}