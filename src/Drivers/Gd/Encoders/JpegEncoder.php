<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Jpeg_Encoder as GenericJpegEncoder;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Jpeg_Encoder extends Generic_Jpeg_Encoder implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image
    {
        $blending_color = $this->driver()->handle_input($this->driver()->config()->blending_color);
        $output = Cloner::clone_blended($image->core()->native(), background: $blending_color);
        return $this->create_encoded_image(function ($pointer) use ($output): void {
            imageinterlace($output, $this->progressive);
            imagejpeg($output, $pointer, $this->quality);
        }, 'image/jpeg');
    }
}