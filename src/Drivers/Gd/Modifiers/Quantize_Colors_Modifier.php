<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\Input_Exception;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Quantize_Colors_Modifier as GenericQuantizeColorsModifier;
class Quantize_Colors_Modifier extends Generic_Quantize_Colors_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        if ($this->limit <= 0) {
            throw new Input_Exception('Quantization limit must be greater than 0.');
        }
        // no color reduction if the limit is higher than the colors in the img
        $color_count = imagecolorstotal($image->core()->native());
        if ($color_count > 0 && $this->limit > $color_count) {
            return $image;
        }
        $width = $image->width();
        $height = $image->height();
        $background = $this->driver()->color_processor($image->colorspace())->color_to_native($this->driver()->handle_input($this->background));
        $blending_color = $this->driver()->handle_input($this->driver()->config()->blending_color);
        foreach ($image as $frame) {
            // create new image for color quantization
            $reduced = Cloner::clone_empty($frame->native(), background: $blending_color);
            // fill with background
            imagefill($reduced, 0, 0, $background);
            // set transparency
            imagecolortransparent($reduced, $background);
            // copy original image (colors are limited automatically in the copy process)
            imagecopy($reduced, $frame->native(), 0, 0, 0, 0, $width, $height);
            // gd library does not support color quantization directly therefore the
            // colors are decrease by transforming the image to a palette version
            imagetruecolortopalette($reduced, true, $this->limit);
            $frame->set_native($reduced);
        }
        return $image;
    }
}