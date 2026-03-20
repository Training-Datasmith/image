<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\Color_Exception;
interface Color_Processor_Interface
{
    /**
     * Turn given color in the driver's color implementation
     *
     * @throws ColorException
     */
    public function color_to_native(Color_Interface $color): mixed;
    /**
     * Turn the given driver's definition of a color into a color object
     *
     * @throws ColorException
     */
    public function native_to_color(mixed $native): Color_Interface;
}