<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
class Slice_Animation_Modifier extends Specializable_Modifier
{
    public function __construct(public int $offset = 0, public ?int $length = null)
    {
    }
}