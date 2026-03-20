<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\Colorspace_Analyzer as GenericColorspaceAnalyzer;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Colorspace_Analyzer extends Generic_Colorspace_Analyzer implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(Image_Interface $image): mixed
    {
        return new Colorspace();
    }
}