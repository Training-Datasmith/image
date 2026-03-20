<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Point_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Place_Modifier as GenericPlaceModifier;
class Place_Modifier extends Generic_Place_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $watermark = $this->driver()->handle_input($this->element);
        $position = $this->get_position($image, $watermark);
        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            if ($this->opacity === 100) {
                $this->place_opaque($frame, $watermark, $position);
            } else {
                $this->place_transparent($frame, $watermark, $position);
            }
        }
        return $image;
    }
    /**
     * Insert watermark with 100% opacity
     *
     * @throws RuntimeException
     */
    private function place_opaque(Frame_Interface $frame, Image_Interface $watermark, Point_Interface $position): void
    {
        imagecopy($frame->native(), $watermark->core()->native(), $position->x(), $position->y(), 0, 0, $watermark->width(), $watermark->height());
    }
    /**
     * Insert watermark transparent with current opacity
     *
     * Unfortunately, the original PHP function imagecopymerge does not work reliably.
     * For example, any transparency of the image to be inserted is not applied correctly.
     * For this reason, a new GDImage is created into which the original image is inserted
     * in the first step and the watermark is inserted with 100% opacity in the second
     * step. This combination is then transferred to the original image again with the
     * respective opacity.
     *
     * Please note: Unfortunately, there is still an edge case, when a transparent image
     * is placed on a transparent background, the "double" transparent areas appear opaque!
     *
     * @throws RuntimeException
     */
    private function place_transparent(Frame_Interface $frame, Image_Interface $watermark, Point_Interface $position): void
    {
        $cut = imagecreatetruecolor($watermark->width(), $watermark->height());
        imagecopy($cut, $frame->native(), 0, 0, $position->x(), $position->y(), imagesx($cut), imagesy($cut));
        imagecopy($cut, $watermark->core()->native(), 0, 0, 0, 0, imagesx($cut), imagesy($cut));
        imagecopymerge($frame->native(), $cut, $position->x(), $position->y(), 0, 0, $watermark->width(), $watermark->height(), $this->opacity);
    }
}