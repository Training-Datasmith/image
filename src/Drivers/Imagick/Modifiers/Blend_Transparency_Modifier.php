<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Blend_Transparency_Modifier as GenericBlendTransparencyModifier;
class Blend_Transparency_Modifier extends Generic_Blend_Transparency_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $blending_color = $this->blending_color($this->driver());
        // get imagickpixel from blending color
        $pixel = $this->driver()->color_processor($image->colorspace())->color_to_native($blending_color);
        // merge transparent areas with the background color
        foreach ($image as $frame) {
            $frame->native()->set_image_background_color($pixel);
            $frame->native()->set_image_alpha_channel(Imagick::ALPHACHANNEL_REMOVE);
            $frame->native()->merge_image_layers(Imagick::LAYERMETHOD_FLATTEN);
        }
        return $image;
    }
}