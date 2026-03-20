<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\Height_Analyzer as GenericHeightAnalyzer;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Height_Analyzer extends Generic_Height_Analyzer implements Specialized_Interface
{
    public function analyze(Image_Interface $image): mixed
    {
        return $image->core()->native()->get_image_height();
    }
}