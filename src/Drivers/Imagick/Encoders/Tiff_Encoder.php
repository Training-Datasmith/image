<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Encoders;

use Intervention\Image\Drivers\Imagick\Modifiers\Strip_Meta_Modifier;
use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Tiff_Encoder as GenericTiffEncoder;
use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Tiff_Encoder extends Generic_Tiff_Encoder implements Specialized_Interface
{
    public function encode(Image_Interface $image): Encoded_Image_Interface
    {
        $format = 'TIFF';
        // strip meta data
        if ($this->strip || is_null($this->strip) && $this->driver()->config()->strip) {
            $image->modify(new Strip_Meta_Modifier());
        }
        $imagick = $image->core()->native();
        $imagick->set_format($format);
        $imagick->set_image_format($format);
        $imagick->set_compression($imagick->get_image_compression());
        $imagick->set_image_compression($imagick->get_image_compression());
        $imagick->set_compression_quality($this->quality);
        $imagick->set_image_compression_quality($this->quality);
        return new Encoded_Image($imagick->get_images_blob(), 'image/tiff');
    }
}