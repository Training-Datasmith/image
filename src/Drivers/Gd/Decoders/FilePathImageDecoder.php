<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Modifiers\Align_Rotation_Modifier;
class File_Path_Image_Decoder extends Native_Object_Decoder implements Decoder_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!$this->is_file($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        // detect media (mime) type
        $media_type = $this->get_media_type_by_file_path($input);
        $image = match ($media_type->format()) {
            // gif files might be animated and therefore cannot
            // be handled by the standard GD decoder.
            Format::GIF => $this->decode_gif($input),
            default => parent::decode(match ($media_type->format()) {
                Format::JPEG => @imagecreatefromjpeg($input),
                Format::WEBP => @imagecreatefromwebp($input),
                Format::PNG => @imagecreatefrompng($input),
                Format::AVIF => @imagecreatefromavif($input),
                Format::BMP => @imagecreatefrombmp($input),
                default => throw new Decoder_Exception('Unable to decode input'),
            }),
        };
        // set file path & mediaType on origin
        $image->origin()->set_file_path($input);
        $image->origin()->set_media_type($media_type);
        // extract exif for the appropriate formats
        if ($media_type->format() === Format::JPEG) {
            $image->set_exif($this->extract_exif_data($input));
        }
        // adjust image orientation
        if ($this->driver()->config()->auto_orientation) {
            $image->modify(new Align_Rotation_Modifier());
        }
        return $image;
    }
}