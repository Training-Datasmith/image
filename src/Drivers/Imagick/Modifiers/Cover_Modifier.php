<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Cover_Modifier as GenericCoverModifier;
class Cover_Modifier extends Generic_Cover_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $crop = $this->get_crop_size($image);
        $resize = $this->get_resize_size($crop);
        foreach ($image as $frame) {
            $frame->native()->crop_image($crop->width(), $crop->height(), $crop->pivot()->x(), $crop->pivot()->y());
            $frame->native()->scale_image($resize->width(), $resize->height());
            $frame->native()->set_image_page(0, 0, 0, 0);
        }
        return $image;
    }
}