<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\Width_Analyzer as GenericWidthAnalyzer;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Width_Analyzer extends Generic_Width_Analyzer implements Specialized_Interface
{
    public function analyze(Image_Interface $image): mixed
    {
        return $image->core()->native()->get_image_width();
    }
}