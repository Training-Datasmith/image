<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Collection;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Modifier_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Strip_Meta_Modifier implements Modifier_Interface, Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see Intervention\Image\Interfaces\ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        // preserve icc profiles
        $profiles = $image->core()->native()->get_image_profiles('icc');
        // remove meta data
        $image->core()->native()->strip_image();
        $image->set_exif(new Collection());
        if ($profiles !== []) {
            // re-apply icc profiles
            $image->core()->native()->profile_image('icc', $profiles['icc']);
        }
        return $image;
    }
}