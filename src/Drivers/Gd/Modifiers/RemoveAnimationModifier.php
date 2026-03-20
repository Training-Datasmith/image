<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Remove_Animation_Modifier as GenericRemoveAnimationModifier;
class Remove_Animation_Modifier extends Generic_Remove_Animation_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $image->core()->set_native($this->selected_frame($image)->native());
        return $image;
    }
}