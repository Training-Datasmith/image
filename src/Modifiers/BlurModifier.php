<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
class Blur_Modifier extends Specializable_Modifier
{
    public function __construct(public int $amount)
    {
    }
}