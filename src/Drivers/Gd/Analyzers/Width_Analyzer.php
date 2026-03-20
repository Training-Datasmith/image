<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\Width_Analyzer as GenericWidthAnalyzer;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Width_Analyzer extends Generic_Width_Analyzer implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(Image_Interface $image): mixed
    {
        return imagesx($image->core()->native());
    }
}