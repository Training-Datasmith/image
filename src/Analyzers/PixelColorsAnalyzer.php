<?php

declare (strict_types=1);
namespace Intervention\Image\Analyzers;

use Intervention\Image\Drivers\Specializable_Analyzer;
class Pixel_Colors_Analyzer extends Specializable_Analyzer
{
    public function __construct(public int $x, public int $y)
    {
    }
}