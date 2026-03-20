<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Profile_Modifier as GenericProfileModifier;
class Profile_Modifier extends Generic_Profile_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $imagick = $image->core()->native();
        $result = $imagick->profile_image('icc', (string) $this->profile);
        if ($result === false) {
            throw new Color_Exception('ICC color profile could not be set.');
        }
        return $image;
    }
}