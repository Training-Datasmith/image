<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Interfaces\Point_Interface;
class Draw_Pixel_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public Point_Interface $position, public mixed $color)
    {
    }
}