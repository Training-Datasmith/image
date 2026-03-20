<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Avif_Encoder as GenericAvifEncoder;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Avif_Encoder extends Generic_Avif_Encoder implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image
    {
        return $this->create_encoded_image(function ($pointer) use ($image): void {
            imageavif($image->core()->native(), $pointer, $this->quality);
        }, 'image/avif');
    }
}