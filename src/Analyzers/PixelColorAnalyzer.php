<?php

declare (strict_types=1);
namespace Intervention\Image\Analyzers;

use Intervention\Image\Drivers\Specializable_Analyzer;
class Pixel_Color_Analyzer extends Specializable_Analyzer
{
    public function __construct(public int $x, public int $y, public int $frame_key = 0)
    {
    }
}