<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\Input_Exception;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Quantize_Colors_Modifier as GenericQuantizeColorsModifier;
class Quantize_Colors_Modifier extends Generic_Quantize_Colors_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        if ($this->limit <= 0) {
            throw new Input_Exception('Quantization limit must be greater than 0.');
        }
        // no color reduction if the limit is higher than the colors in the img
        if ($this->limit > $image->core()->native()->get_image_colors()) {
            return $image;
        }
        foreach ($image as $frame) {
            $frame->native()->quantize_image($this->limit, $frame->native()->get_image_colorspace(), 0, false, false);
        }
        return $image;
    }
}