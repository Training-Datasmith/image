<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\Analyzer_Interface;
use Intervention\Image\Interfaces\Image_Interface;
abstract class Specializable_Analyzer extends Specializable implements Analyzer_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(Image_Interface $image): mixed
    {
        return $image->analyze($this);
    }
}