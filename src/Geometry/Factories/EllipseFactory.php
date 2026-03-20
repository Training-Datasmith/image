<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry\Factories;

use Closure;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\Drawable_Factory_Interface;
use Intervention\Image\Interfaces\Drawable_Interface;
use Intervention\Image\Interfaces\Point_Interface;
class Ellipse_Factory implements Drawable_Factory_Interface
{
    protected Ellipse $ellipse;
    /**
     * Create new factory instance
     */
    public function __construct(protected Point_Interface $pivot = new Point(), null|Closure|Ellipse $init = null)
    {
        $this->ellipse = is_a($init, Ellipse::class) ? $init : new Ellipse(0, 0);
        $this->ellipse->set_position($pivot);
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
        return $this->ellipse;
    }
    /**
     * Set the size of the ellipse to be produced
     */
    public function size(int $width, int $height): self
    {
        $this->ellipse->set_size($width, $height);
        return $this;
    }
    /**
     * Set the width of the ellipse to be produced
     */
    public function width(int $width): self
    {
        $this->ellipse->set_width($width);
        return $this;
    }
    /**
     * Set the height of the ellipse to be produced
     */
    public function height(int $height): self
    {
        $this->ellipse->set_height($height);
        return $this;
    }
    /**
     * Set the background color of the ellipse to be produced
     */
    public function background(mixed $color): self
    {
        $this->ellipse->set_background_color($color);
        return $this;
    }
    /**
     * Set the border color & border size of the ellipse to be produced
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->ellipse->set_border($color, $size);
        return $this;
    }
    /**
     * Produce the ellipse
     */
    public function __invoke(): Ellipse
    {
        return $this->ellipse;
    }
}