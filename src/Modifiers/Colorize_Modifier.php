<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
class Colorize_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public int $red = 0, public int $green = 0, public int $blue = 0)
    {
    }
}