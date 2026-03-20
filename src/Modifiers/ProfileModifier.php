<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Interfaces\Profile_Interface;
class Profile_Modifier extends Specializable_Modifier
{
    public function __construct(public Profile_Interface $profile)
    {
    }
}