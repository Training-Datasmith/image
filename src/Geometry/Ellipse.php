<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Traits\Has_Background_Color;
use Intervention\Image\Geometry\Traits\Has_Border;
use Intervention\Image\Interfaces\Drawable_Interface;
use Intervention\Image\Interfaces\Point_Interface;
class Ellipse implements Drawable_Interface
{
    use Has_Border;
    use Has_Background_Color;
    /**
     * Create new Ellipse
     */
    public function __construct(protected int $width, protected int $height, protected Point_Interface $pivot = new Point())
    {
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::position()
     */
    public function position(): Point_Interface
    {
        return $this->pivot;
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setPosition()
     */
    public function set_position(Point_Interface $position): self
    {
        $this->pivot = $position;
        return $this;
    }
    /**
     * Return pivot point of Ellipse
     */
    public function pivot(): Point_Interface
    {
        return $this->pivot;
    }
    /**
     * Set size of Ellipse
     */
    public function set_size(int $width, int $height): self
    {
        return $this->set_width($width)->set_height($height);
    }
    /**
     * Set width of Ellipse
     */
    public function set_width(int $width): self
    {
        $this->width = $width;
        return $this;
    }
    /**
     * Set height of Ellipse
     */
    public function set_height(int $height): self
    {
        $this->height = $height;
        return $this;
    }
    /**
     * Get width of Ellipse
     */
    public function width(): int
    {
        return $this->width;
    }
    /**
     * Get height of Ellipse
     */
    public function height(): int
    {
        return $this->height;
    }
}