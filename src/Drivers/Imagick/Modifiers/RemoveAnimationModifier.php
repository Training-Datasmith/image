<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Remove_Animation_Modifier as GenericRemoveAnimationModifier;
class Remove_Animation_Modifier extends Generic_Remove_Animation_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        // create new imagick with just one image
        $imagick = new Imagick();
        $frame = $this->selected_frame($image);
        $imagick->add_image($frame->native()->get_image());
        // set new imagick to image
        $image->core()->set_native($imagick);
        return $image;
    }
}