<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Imagick;
use Intervention\Image\Analyzers\Colorspace_Analyzer as GenericColorspaceAnalyzer;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Colorspace_Analyzer extends Generic_Colorspace_Analyzer implements Specialized_Interface
{
    public function analyze(Image_Interface $image): mixed
    {
        return match ($image->core()->native()->get_image_colorspace()) {
            Imagick::COLORSPACE_CMYK => new Cmyk_Colorspace(),
            default => new Rgb_Colorspace(),
        };
    }
}