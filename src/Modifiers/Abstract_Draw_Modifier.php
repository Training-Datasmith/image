<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Drawable_Interface;
use RuntimeException;
abstract class Abstract_Draw_Modifier extends Specializable_Modifier
{
    /**
     * Return the drawable object which will be rendered by the modifier
     */
    abstract public function drawable(): Drawable_Interface;
    /**
     * @throws RuntimeException
     */
    public function background_color(): Color_Interface
    {
        try {
            $color = $this->driver()->handle_input($this->drawable()->background_color());
        } catch (Decoder_Exception) {
            return $this->driver()->handle_input('transparent');
        }
        return $color;
    }
    /**
     * @throws RuntimeException
     */
    public function border_color(): Color_Interface
    {
        try {
            $color = $this->driver()->handle_input($this->drawable()->border_color());
        } catch (Decoder_Exception) {
            return $this->driver()->handle_input('transparent');
        }
        return $color;
    }
}