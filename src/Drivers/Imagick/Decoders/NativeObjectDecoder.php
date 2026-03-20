<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Specializable_Decoder;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Align_Rotation_Modifier;
use Intervention\Image\Modifiers\Remove_Animation_Modifier;
class Native_Object_Decoder extends Specializable_Decoder implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_object($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        if (!$input instanceof Imagick) {
            throw new Decoder_Exception('Unable to decode input');
        }
        // For some JPEG formats, the "coalesceImages()" call leads to an image
        // completely filled with background color. The logic behind this is
        // incomprehensible for me; could be an imagick bug.
        if ($input->get_image_format() !== 'JPEG') {
            $input = $input->coalesce_images();
        }
        // turn images with colorspace 'GRAY' into 'SRGB' to avoid working on
        // greyscale colorspace images as this results images loosing color
        // information when placed into this image.
        if ($input->get_image_colorspace() == Imagick::COLORSPACE_GRAY) {
            $input->set_image_colorspace(Imagick::COLORSPACE_SRGB);
        }
        // create image object
        $image = new Image($this->driver(), new Core($input));
        // discard animation depending on config
        if (!$this->driver()->config()->decode_animation) {
            $image->modify(new Remove_Animation_Modifier());
        }
        // adjust image rotatation
        if ($this->driver()->config()->auto_orientation) {
            $image->modify(new Align_Rotation_Modifier());
        }
        // set media type on origin
        $image->origin()->set_media_type($input->get_image_mime_type());
        return $image;
    }
}