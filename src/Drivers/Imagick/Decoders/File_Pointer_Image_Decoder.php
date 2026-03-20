<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class File_Pointer_Image_Decoder extends Binary_Image_Decoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_resource($input) || !in_array(get_resource_type($input), ['file', 'stream'])) {
            throw new Decoder_Exception('Unable to decode input');
        }
        $contents = '';
        @rewind($input);
        while (!feof($input)) {
            $contents .= fread($input, 1024);
        }
        return parent::decode($contents);
    }
}