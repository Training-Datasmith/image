<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Align_Rotation_Modifier as GenericAlignRotationModifier;
class Align_Rotation_Modifier extends Generic_Align_Rotation_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        switch ($image->core()->native()->get_image_orientation()) {
            case Imagick::ORIENTATION_TOPRIGHT:
                // 2
                $image->core()->native()->flop_image();
                break;
            case Imagick::ORIENTATION_BOTTOMRIGHT:
                // 3
                $image->core()->native()->rotate_image('#000', 180);
                break;
            case Imagick::ORIENTATION_BOTTOMLEFT:
                // 4
                $image->core()->native()->rotate_image('#000', 180);
                $image->core()->native()->flop_image();
                break;
            case Imagick::ORIENTATION_LEFTTOP:
                // 5
                $image->core()->native()->rotate_image('#000', -270);
                $image->core()->native()->flop_image();
                break;
            case Imagick::ORIENTATION_RIGHTTOP:
                // 6
                $image->core()->native()->rotate_image('#000', -270);
                break;
            case Imagick::ORIENTATION_RIGHTBOTTOM:
                // 7
                $image->core()->native()->rotate_image('#000', -90);
                $image->core()->native()->flop_image();
                break;
            case Imagick::ORIENTATION_LEFTBOTTOM:
                // 8
                $image->core()->native()->rotate_image('#000', -90);
                break;
        }
        // set new orientation in image
        $image->core()->native()->set_image_orientation(Imagick::ORIENTATION_TOPLEFT);
        return $image;
    }
}