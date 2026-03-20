<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use Imagick_Exception;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class File_Path_Image_Decoder extends Native_Object_Decoder
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
        try {
            $imagick = new Imagick();
            $imagick->read_image($input);
        } catch (Imagick_Exception) {
            throw new Decoder_Exception('Unable to decode input');
        }
        // decode image
        $image = parent::decode($imagick);
        // set file path on origin
        $image->origin()->set_file_path($input);
        // extract exif data for the appropriate formats
        if (in_array($imagick->get_image_format(), ['JPEG', 'TIFF', 'TIF'])) {
            $image->set_exif($this->extract_exif_data($input));
        }
        return $image;
    }
}