<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Driver_Interface;
class Blend_Transparency_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public mixed $color = null)
    {
    }
    /**
     * Decode blending color of current modifier with given driver. Possible
     * (semi-)transparent alpha channel values are made opaque.
     *
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function blending_color(Driver_Interface $driver): Color_Interface
    {
        // decode blending color
        $color = $driver->handle_input($this->color ?: $driver->config()->blending_color);
        // replace alpha channel value with opaque value
        if ($color->is_transparent()) {
            return new Color($color->channel(Red::class)->value(), $color->channel(Green::class)->value(), $color->channel(Blue::class)->value());
        }
        return $color;
    }
}