<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Interfaces\Point_Interface;
class Fill_Modifier extends Specializable_Modifier
{
    public function __construct(public mixed $color, public ?Point_Interface $position = null)
    {
    }
    public function has_position(): bool
    {
        return $this->position instanceof Point_Interface;
    }
}