<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
class Rotate_Modifier extends Specializable_Modifier
{
    public function __construct(public float $angle, public mixed $background)
    {
    }
    /**
     * Restrict rotations beyond 360 degrees
     * because the end result is the same
     */
    public function rotation_angle(): float
    {
        return fmod($this->angle, 360);
    }
}