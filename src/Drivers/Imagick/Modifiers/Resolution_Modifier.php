<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Resolution_Modifier as GenericResolutionModifier;
class Resolution_Modifier extends Generic_Resolution_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $imagick = $image->core()->native();
        $imagick->set_image_resolution($this->x, $this->y);
        return $image;
    }
}