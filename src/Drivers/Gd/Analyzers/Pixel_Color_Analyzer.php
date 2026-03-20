<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Analyzers;

use Gd_Image;
use Intervention\Image\Analyzers\Pixel_Color_Analyzer as GenericPixelColorAnalyzer;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Exceptions\Geometry_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Pixel_Color_Analyzer extends Generic_Pixel_Color_Analyzer implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(Image_Interface $image): mixed
    {
        return $this->color_at($image->colorspace(), $image->core()->frame($this->frame_key)->native());
    }
    /**
     * @throws GeometryException
     * @throws ColorException
     */
    protected function color_at(Colorspace_Interface $colorspace, Gd_Image $gd): Color_Interface
    {
        $index = @imagecolorat($gd, $this->x, $this->y);
        if (!imageistruecolor($gd)) {
            $index = imagecolorsforindex($gd, $index);
        }
        if ($index === false) {
            throw new Geometry_Exception('The specified position is not in the valid image area.');
        }
        return $this->driver()->color_processor($colorspace)->native_to_color($index);
    }
}