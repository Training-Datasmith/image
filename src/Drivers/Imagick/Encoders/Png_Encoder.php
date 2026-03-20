<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Png_Encoder as GenericPngEncoder;
use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Png_Encoder extends Generic_Png_Encoder implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image_Interface
    {
        if ($this->indexed) {
            // reduce colors
            $output = clone $image;
            $output->reduce_colors(256);
            $output = $output->core()->native();
            $output->set_format('PNG');
            $output->set_image_format('PNG');
        } else {
            $output = clone $image->core()->native();
            $output->set_format('PNG32');
            $output->set_image_format('PNG32');
        }
        $output->set_compression(Imagick::COMPRESSION_ZIP);
        $output->set_image_compression(Imagick::COMPRESSION_ZIP);
        if ($this->interlaced) {
            $output->set_interlace_scheme(Imagick::INTERLACE_LINE);
        }
        return new Encoded_Image($output->get_images_blob(), 'image/png');
    }
}