<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
class Resize_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public ?int $width = null, public ?int $height = null)
    {
    }
}