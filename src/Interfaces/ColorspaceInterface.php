<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

interface Colorspace_Interface
{
    /**
     * Convert given color to the format of the current colorspace
     */
    public function import_color(Color_Interface $color): Color_Interface;
    /**
     * Create new color in colorspace from given normalized channel values
     *
     * @param array<float> $normalized
     */
    public function color_from_normalized(array $normalized): Color_Interface;
}