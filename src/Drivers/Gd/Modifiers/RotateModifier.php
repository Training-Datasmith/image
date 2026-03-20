<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Rotate_Modifier as GenericRotateModifier;
class Rotate_Modifier extends Generic_Rotate_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $background = $this->driver()->handle_input($this->background);
        foreach ($image as $frame) {
            $this->modify_frame($frame, $background);
        }
        return $image;
    }
    /**
     * Apply rotation modification on given frame, given background
     * color is used for newly create image areas
     *
     * @throws ColorException
     */
    protected function modify_frame(Frame_Interface $frame, Color_Interface $background): void
    {
        // get transparent color from frame core
        $transparent = match ($transparent = imagecolortransparent($frame->native())) {
            -1 => imagecolorallocatealpha($frame->native(), $background->channel(Red::class)->value(), $background->channel(Green::class)->value(), $background->channel(Blue::class)->value(), 127),
            default => $transparent,
        };
        // rotate original image against transparent background
        $rotated = imagerotate($frame->native(), $this->rotation_angle(), $transparent);
        // create size from original after rotation
        $container = (new Rectangle(imagesx($rotated), imagesy($rotated)))->move_pivot('center');
        // create size from original and rotate points
        $cutout = (new Rectangle(imagesx($frame->native()), imagesy($frame->native()), $container->pivot()))->align('center')->valign('center')->rotate($this->rotation_angle() * -1);
        // create new gd image
        $modified = Cloner::clone_empty($frame->native(), $container, $background);
        // draw the cutout on new gd image to have a transparent
        // background where the rotated image will be placed
        imagealphablending($modified, false);
        imagefilledpolygon($modified, $cutout->to_array(), imagecolortransparent($modified));
        // place rotated image on new gd image
        imagealphablending($modified, true);
        imagecopy($modified, $rotated, 0, 0, 0, 0, imagesx($rotated), imagesy($rotated));
        $frame->set_native($modified);
    }
}