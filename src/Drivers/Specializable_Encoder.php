<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\Specializable_Interface;
use Intervention\Image\Traits\Can_Be_Driver_Specialized;
abstract class Specializable_Encoder extends Abstract_Encoder implements Specializable_Interface
{
    use Can_Be_Driver_Specialized;
}