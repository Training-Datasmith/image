<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\Animation_Exception;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Slice_Animation_Modifier as GenericSliceAnimationModifier;
class Slice_Animation_Modifier extends Generic_Slice_Animation_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        if ($this->offset >= $image->count()) {
            throw new Animation_Exception('Offset is not in the range of frames.');
        }
        $image->core()->slice($this->offset, $this->length);
        return $image;
    }
}