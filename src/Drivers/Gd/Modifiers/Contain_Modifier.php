<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Contain_Modifier as GenericContainModifier;
class Contain_Modifier extends Generic_Contain_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $crop = $this->get_crop_size($image);
        $resize = $this->get_resize_size($image);
        $background = $this->driver()->handle_input($this->background);
        $blending_color = $this->driver()->handle_input($this->driver()->config()->blending_color);
        foreach ($image as $frame) {
            $this->modify($frame, $crop, $resize, $background, $blending_color);
        }
        return $image;
    }
    /**
     * @throws ColorException
     */
    protected function modify(Frame_Interface $frame, Size_Interface $crop, Size_Interface $resize, Color_Interface $background, Color_Interface $blending_color): void
    {
        // create new gd image
        $modified = Cloner::clone_empty($frame->native(), $resize, $background);
        // make image area transparent to keep transparency
        // even if background-color is set
        $transparent = imagecolorallocatealpha($modified, $blending_color->channel(Red::class)->value(), $blending_color->channel(Green::class)->value(), $blending_color->channel(Blue::class)->value(), 127);
        imagealphablending($modified, false);
        // do not blend / just overwrite
        imagecolortransparent($modified, $transparent);
        imagefilledrectangle($modified, $crop->pivot()->x(), $crop->pivot()->y(), $crop->pivot()->x() + $crop->width() - 1, $crop->pivot()->y() + $crop->height() - 1, $transparent);
        // copy image from original with blending alpha
        imagealphablending($modified, true);
        imagecopyresampled($modified, $frame->native(), $crop->pivot()->x(), $crop->pivot()->y(), 0, 0, $crop->width(), $crop->height(), $frame->size()->width(), $frame->size()->height());
        // set new content as resource
        $frame->set_native($modified);
    }
}