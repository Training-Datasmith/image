<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Resize_Canvas_Modifier as GenericResizeCanvasModifier;
class Resize_Canvas_Modifier extends Generic_Resize_Canvas_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $crop_size = $this->crop_size($image);
        $image->modify(new Crop_Modifier($crop_size->width(), $crop_size->height(), $crop_size->pivot()->x(), $crop_size->pivot()->y(), $this->background));
        return $image;
    }
}