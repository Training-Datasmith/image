<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Crop_Modifier as GenericCropModifier;
class Crop_Modifier extends Generic_Crop_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $original_size = $image->size();
        $crop = $this->crop($image);
        $background = $this->driver()->handle_input($this->background);
        foreach ($image as $frame) {
            $this->crop_frame($frame, $original_size, $crop, $background);
        }
        return $image;
    }
    /**
     * @throws ColorException
     */
    protected function crop_frame(Frame_Interface $frame, Size_Interface $original_size, Size_Interface $resize_to, Color_Interface $background): void
    {
        // create new image with transparent background
        $modified = Cloner::clone_empty($frame->native(), $resize_to, $background);
        // define offset
        $offset_x = $resize_to->pivot()->x() + $this->offset_x;
        $offset_y = $resize_to->pivot()->y() + $this->offset_y;
        // define target width & height
        $target_width = min($resize_to->width(), $original_size->width());
        $target_height = min($resize_to->height(), $original_size->height());
        $target_width = $target_width < $original_size->width() ? $target_width + $offset_x : $target_width;
        $target_height = $target_height < $original_size->height() ? $target_height + $offset_y : $target_height;
        // don't alpha blend for copy operation to keep transparent areas of original image
        imagealphablending($modified, false);
        // copy content from resource
        imagecopyresampled($modified, $frame->native(), $offset_x * -1, $offset_y * -1, 0, 0, $target_width, $target_height, $target_width, $target_height);
        // set new content as resource
        $frame->set_native($modified);
    }
}