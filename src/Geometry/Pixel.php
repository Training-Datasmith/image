<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\Color_Interface;
class Pixel extends Point
{
    /**
     * Create new pixel instance
     */
    public function __construct(protected Color_Interface $background, protected int $x, protected int $y)
    {
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBackgroundColor()
     */
    public function set_background_color(Color_Interface $background): self
    {
        $this->background = $background;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::backgroundColor()
     */
    public function background_color(): Color_Interface
    {
        return $this->background;
    }
}