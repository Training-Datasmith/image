<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Colorspace_Modifier as GenericColorspaceModifier;
class Colorspace_Modifier extends Generic_Colorspace_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        if (!$this->target_colorspace() instanceof Rgb_Colorspace) {
            throw new Not_Supported_Exception('Only RGB colorspace is supported by GD driver.');
        }
        return $image;
    }
}