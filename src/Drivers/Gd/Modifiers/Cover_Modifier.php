<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Cover_Modifier as GenericCoverModifier;
class Cover_Modifier extends Generic_Cover_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $crop = $this->get_crop_size($image);
        $resize = $this->get_resize_size($crop);
        foreach ($image as $frame) {
            $this->modify_frame($frame, $crop, $resize);
        }
        return $image;
    }
    /**
     * @throws ColorException
     */
    protected function modify_frame(Frame_Interface $frame, Size_Interface $crop, Size_Interface $resize): void
    {
        // create new image
        $modified = Cloner::clone_empty($frame->native(), $resize);
        // copy content from resource
        imagecopyresampled($modified, $frame->native(), 0, 0, $crop->pivot()->x(), $crop->pivot()->y(), $resize->width(), $resize->height(), $crop->width(), $crop->height());
        // set new content as resource
        $frame->set_native($modified);
    }
}