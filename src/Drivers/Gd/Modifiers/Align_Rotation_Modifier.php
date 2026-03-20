<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Align_Rotation_Modifier as GenericAlignRotationModifier;
class Align_Rotation_Modifier extends Generic_Align_Rotation_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $image = match ($image->exif('IFD0.Orientation')) {
            2 => $image->flop(),
            3 => $image->rotate(180),
            4 => $image->rotate(180)->flop(),
            5 => $image->rotate(270)->flop(),
            6 => $image->rotate(270),
            7 => $image->rotate(90)->flop(),
            8 => $image->rotate(90),
            default => $image,
        };
        return $this->mark_aligned($image);
    }
    /**
     * Set exif data of image to top-left orientation, marking the image as
     * aligned and making sure the rotation correction process is not
     * performed again.
     */
    private function mark_aligned(Image_Interface $image): Image_Interface
    {
        $exif = $image->exif()->map(function ($item) {
            if (is_array($item) && array_key_exists('Orientation', $item)) {
                $item['Orientation'] = 1;
                return $item;
            }
            return $item;
        });
        return $image->set_exif($exif);
    }
}