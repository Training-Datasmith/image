<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use Imagick_Exception;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Binary_Image_Decoder extends Native_Object_Decoder
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
        try {
            $imagick = new Imagick();
            $imagick->read_image_blob($input);
        } catch (Imagick_Exception) {
            throw new Decoder_Exception('Unable to decode input');
        }
        // decode image
        $image = parent::decode($imagick);
        // get media type enum from string media type
        $format = Format::try_create($image->origin()->media_type());
        // extract exif data for appropriate formats
        if (in_array($format, [Format::JPEG, Format::TIFF])) {
            $image->set_exif($this->extract_exif_data($input));
        }
        return $image;
    }
}