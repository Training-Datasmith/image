<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Bmp_Encoder as GenericBmpEncoder;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Bmp_Encoder extends Generic_Bmp_Encoder implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image
    {
        return $this->create_encoded_image(function ($pointer) use ($image): void {
            imagebmp($image->core()->native(), $pointer, false);
        }, 'image/bmp');
    }
}