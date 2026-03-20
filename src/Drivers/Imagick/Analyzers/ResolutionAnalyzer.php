<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\Resolution_Analyzer as GenericResolutionAnalyzer;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Resolution;
class Resolution_Analyzer extends Generic_Resolution_Analyzer implements Specialized_Interface
{
    public function analyze(Image_Interface $image): mixed
    {
        $imagick = $image->core()->native();
        $image_resolution = $imagick->get_image_resolution();
        return new Resolution($image_resolution['x'], $image_resolution['y'], $imagick->get_image_units());
    }
}