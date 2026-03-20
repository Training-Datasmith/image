<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;
interface Modifier_Interface
{
    /**
     * Apply modifications of the current modifier to the given image
     *
     * @throws RuntimeException
     */
    public function apply(Image_Interface $image): Image_Interface;
}