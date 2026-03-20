<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Modifier_Interface;
abstract class Specializable_Modifier extends Specializable implements Modifier_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        return $image->modify($this);
    }
}