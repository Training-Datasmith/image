<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Bmp_Encoder as GenericBmpEncoder;
use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Bmp_Encoder extends Generic_Bmp_Encoder implements Specialized_Interface
{
    public function encode(Image_Interface $image): Encoded_Image_Interface
    {
        $format = 'BMP';
        $compression = Imagick::COMPRESSION_NO;
        $imagick = $image->core()->native();
        $imagick->set_format($format);
        $imagick->set_image_format($format);
        $imagick->set_compression($compression);
        $imagick->set_image_compression($compression);
        return new Encoded_Image($imagick->get_images_blob(), 'image/bmp');
    }
}