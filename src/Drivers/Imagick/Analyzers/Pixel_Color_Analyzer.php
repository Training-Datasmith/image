<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Imagick;
use Intervention\Image\Analyzers\Pixel_Color_Analyzer as GenericPixelColorAnalyzer;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Pixel_Color_Analyzer extends Generic_Pixel_Color_Analyzer implements Specialized_Interface
{
    public function analyze(Image_Interface $image): mixed
    {
        return $this->color_at($image->colorspace(), $image->core()->frame($this->frame_key)->native());
    }
    /**
     * @throws ColorException
     */
    protected function color_at(Colorspace_Interface $colorspace, Imagick $imagick): Color_Interface
    {
        return $this->driver()->color_processor($colorspace)->native_to_color($imagick->get_image_pixel_color($this->x, $this->y));
    }
}