<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Trim_Modifier as GenericTrimModifier;
class Trim_Modifier extends Generic_Trim_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        if ($image->is_animated()) {
            throw new Not_Supported_Exception('Trim modifier cannot be applied to animated images.');
        }
        $imagick = $image->core()->native();
        $imagick->trim_image($this->tolerance / 100 * $imagick->get_quantum() / 1.5);
        $imagick->set_image_page(0, 0, 0, 0);
        return $image;
    }
}