<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
class Resolution_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public float $x, public float $y)
    {
    }
}