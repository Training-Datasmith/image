<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Encoders;

use Gd_Image;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Png_Encoder as GenericPngEncoder;
use Intervention\Image\Exceptions\Animation_Exception;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Png_Encoder extends Generic_Png_Encoder implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image
    {
        $output = $this->prepare_output($image);
        return $this->create_encoded_image(function ($pointer) use ($output): void {
            imageinterlace($output, $this->interlaced);
            imagepng($output, $pointer, -1);
        }, 'image/png');
    }
    /**
     * Prepare given image instance for PNG format output according to encoder settings
     *
     * @throws RuntimeException
     * @throws ColorException
     * @throws AnimationException
     */
    private function prepare_output(Image_Interface $image): Gd_Image
    {
        if ($this->indexed) {
            $output = clone $image;
            $output->reduce_colors(255);
            return $output->core()->native();
        }
        return Cloner::clone($image->core()->native());
    }
}