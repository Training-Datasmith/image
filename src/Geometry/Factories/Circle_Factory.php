<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry\Factories;

use Closure;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\Drawable_Factory_Interface;
use Intervention\Image\Interfaces\Drawable_Interface;
use Intervention\Image\Interfaces\Point_Interface;
class Circle_Factory implements Drawable_Factory_Interface
{
    protected Circle $circle;
    /**
     * Create new factory instance
     */
    public function __construct(protected Point_Interface $pivot = new Point(), null|Closure|Circle $init = null)
    {
        $this->circle = is_a($init, Circle::class) ? $init : new Circle(0);
        $this->circle->set_position($pivot);
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
        return $this->circle;
    }
    /**
     * Set the radius of the circle to be produced
     */
    public function radius(int $radius): self
    {
        $this->circle->set_size($radius * 2, $radius * 2);
        return $this;
    }
    /**
     * Set the diameter of the circle to be produced
     */
    public function diameter(int $diameter): self
    {
        $this->circle->set_size($diameter, $diameter);
        return $this;
    }
    /**
     * Set the background color of the circle to be produced
     */
    public function background(mixed $color): self
    {
        $this->circle->set_background_color($color);
        return $this;
    }
    /**
     * Set the border color & border size of the ellipse to be produced
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->circle->set_border($color, $size);
        return $this;
    }
    /**
     * Produce the circle
     */
    public function __invoke(): Circle
    {
        return $this->circle;
    }
}