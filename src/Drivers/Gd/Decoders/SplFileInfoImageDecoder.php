<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Spl_File_Info;
class Spl_File_Info_Image_Decoder extends File_Path_Image_Decoder implements Decoder_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_a($input, Spl_File_Info::class)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        return parent::decode($input->get_real_path());
    }
}