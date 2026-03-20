<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Blend_Transparency_Modifier as GenericBlendTransparencyModifier;
class Blend_Transparency_Modifier extends Generic_Blend_Transparency_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $blending_color = $this->blending_color($this->driver());
        foreach ($image as $frame) {
            // create new canvas with blending color as background
            $modified = Cloner::clone_blended($frame->native(), background: $blending_color);
            // set new gd image
            $frame->set_native($modified);
        }
        return $image;
    }
}