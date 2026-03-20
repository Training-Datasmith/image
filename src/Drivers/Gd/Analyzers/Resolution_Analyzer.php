<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\Resolution_Analyzer as GenericResolutionAnalyzer;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Resolution;
class Resolution_Analyzer extends Generic_Resolution_Analyzer implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(Image_Interface $image): mixed
    {
        return new Resolution(...imageresolution($image->core()->native()));
    }
}