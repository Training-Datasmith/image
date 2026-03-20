<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Exceptions\Geometry_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Resize_Modifier as GenericResizeModifier;
class Resize_Modifier extends Generic_Resize_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $resize_to = $this->get_adjusted_size($image);
        foreach ($image as $frame) {
            $this->resize_frame($frame, $resize_to);
        }
        return $image;
    }
    /**
     * @throws ColorException
     */
    private function resize_frame(Frame_Interface $frame, Size_Interface $resize_to): void
    {
        // create empty canvas in target size
        $modified = Cloner::clone_empty($frame->native(), $resize_to);
        // copy content from resource
        imagecopyresampled($modified, $frame->native(), $resize_to->pivot()->x(), $resize_to->pivot()->y(), 0, 0, $resize_to->width(), $resize_to->height(), $frame->size()->width(), $frame->size()->height());
        // set new content as resource
        $frame->set_native($modified);
    }
    /**
     * Return the size the modifier will resize to
     *
     * @throws RuntimeException
     * @throws GeometryException
     */
    protected function get_adjusted_size(Image_Interface $image): Size_Interface
    {
        return $image->size()->resize($this->width, $this->height);
    }
}