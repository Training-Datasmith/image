<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Collection;
use Intervention\Image\Interfaces\Image_Interface;
class Pixel_Colors_Analyzer extends Pixel_Color_Analyzer
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(Image_Interface $image): mixed
    {
        $colors = new Collection();
        $colorspace = $image->colorspace();
        foreach ($image as $frame) {
            $colors->push(parent::color_at($colorspace, $frame->native()));
        }
        return $colors;
    }
}