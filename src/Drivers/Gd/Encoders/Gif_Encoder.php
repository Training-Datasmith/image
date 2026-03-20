<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Encoders;

use Exception;
use Intervention\Gif\Builder as GifBuilder;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Encoded_Image;
use Intervention\Image\Encoders\Gif_Encoder as GenericGifEncoder;
use Intervention\Image\Exceptions\Encoder_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Gif_Encoder extends Generic_Gif_Encoder implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(Image_Interface $image): Encoded_Image
    {
        if ($image->is_animated()) {
            return $this->encode_animated($image);
        }
        $gd = Cloner::clone($image->core()->native());
        return $this->create_encoded_image(function ($pointer) use ($gd): void {
            imageinterlace($gd, $this->interlaced);
            imagegif($gd, $pointer);
        }, 'image/gif');
    }
    /**
     * @throws RuntimeException
     */
    protected function encode_animated(Image_Interface $image): Encoded_Image
    {
        try {
            $builder = Gif_Builder::canvas($image->width(), $image->height());
            foreach ($image as $frame) {
                $builder->add_frame(source: $this->encode($frame->to_image($image->driver()))->to_file_pointer(), delay: $frame->delay(), interlaced: $this->interlaced);
            }
            $builder->set_loops($image->loops());
            return new Encoded_Image($builder->encode(), 'image/gif');
        } catch (Exception $e) {
            throw new Encoder_Exception($e->get_message(), $e->get_code(), $e);
        }
    }
}