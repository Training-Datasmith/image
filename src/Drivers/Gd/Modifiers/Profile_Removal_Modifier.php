<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Profile_Removal_Modifier as GenericProfileRemovalModifier;
class Profile_Removal_Modifier extends Generic_Profile_Removal_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        // Color profiles are not supported by GD, so the decoded
        // image is already free of profiles anyway.
        return $image;
    }
}