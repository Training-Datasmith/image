<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Webp_Encoder as GenericWebpEncoder;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Webp_Encoder extends Generic_Webp_Encoder implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image
    {
        $quality = $this->quality === 100 && defined('IMG_WEBP_LOSSLESS') ? IMG_WEBP_LOSSLESS : $this->quality;
        return $this->create_encoded_image(function ($pointer) use ($image, $quality): void {
            imagewebp($image->core()->native(), $pointer, $quality);
        }, 'image/webp');
    }
}