<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Modifiers\Align_Rotation_Modifier;
class Binary_Image_Decoder extends Native_Object_Decoder implements Decoder_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_string($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        return match ($this->is_gif_format($input)) {
            true => $this->decode_gif($input),
            default => $this->decode_binary($input),
        };
    }
    /**
     * Decode image from given binary data
     *
     * @throws RuntimeException
     */
    private function decode_binary(string $input): Image_Interface
    {
        $gd = @imagecreatefromstring($input);
        if ($gd === false) {
            throw new Decoder_Exception('Unable to decode input');
        }
        // create image instance
        $image = parent::decode($gd);
        // get media type
        $media_type = $this->get_media_type_by_binary($input);
        // extract & set exif data for appropriate formats
        if (in_array($media_type->format(), [Format::JPEG, Format::TIFF])) {
            $image->set_exif($this->extract_exif_data($input));
        }
        // set mediaType on origin
        $image->origin()->set_media_type($media_type);
        // adjust image orientation
        if ($this->driver()->config()->auto_orientation) {
            $image->modify(new Align_Rotation_Modifier());
        }
        return $image;
    }
}