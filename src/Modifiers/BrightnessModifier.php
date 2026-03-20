<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
class Brightness_Modifier extends Specializable_Modifier
{
    public function __construct(public int $level)
    {
    }
}