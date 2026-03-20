<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry\Factories;

use Closure;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Drawable_Factory_Interface;
use Intervention\Image\Interfaces\Drawable_Interface;
use Intervention\Image\Interfaces\Point_Interface;
class Rectangle_Factory implements Drawable_Factory_Interface
{
    protected Rectangle $rectangle;
    /**
     * Create new instance
     */
    public function __construct(protected Point_Interface $pivot = new Point(), null|Closure|Rectangle $init = null)
    {
        $this->rectangle = is_a($init, Rectangle::class) ? $init : new Rectangle(0, 0, $pivot);
        $this->rectangle->set_position($pivot);
        if (is_callable($init)) {
            $init($this);
        }
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::init()
     */
    public static function init(null|Closure|Drawable_Interface $init = null): self
    {
        return new self(init: $init);
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public function create(): Drawable_Interface
    {
        return $this->rectangle;
    }
    /**
     * Set the size of the rectangle to be produced
     */
    public function size(int $width, int $height): self
    {
        $this->rectangle->set_size($width, $height);
        return $this;
    }
    /**
     * Set the width of the rectangle to be produced
     */
    public function width(int $width): self
    {
        $this->rectangle->set_width($width);
        return $this;
    }
    /**
     * Set the height of the rectangle to be produced
     */
    public function height(int $height): self
    {
        $this->rectangle->set_height($height);
        return $this;
    }
    /**
     * Set the background color of the rectangle to be produced
     */
    public function background(mixed $color): self
    {
        $this->rectangle->set_background_color($color);
        return $this;
    }
    /**
     * Set the border color & border size of the rectangle to be produced
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->rectangle->set_border($color, $size);
        return $this;
    }
    /**
     * Produce the rectangle
     */
    public function __invoke(): Rectangle
    {
        return $this->rectangle;
    }
}