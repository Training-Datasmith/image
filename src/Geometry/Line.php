<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Traits\Has_Background_Color;
use Intervention\Image\Geometry\Traits\Has_Border;
use Intervention\Image\Interfaces\Drawable_Interface;
use Intervention\Image\Interfaces\Point_Interface;
class Line implements Drawable_Interface
{
    use Has_Border;
    use Has_Background_Color;
    /**
     * Create new line instance
     */
    public function __construct(protected Point_Interface $start, protected Point_Interface $end, protected int $width = 1)
    {
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::position()
     */
    public function position(): Point_Interface
    {
        return $this->start;
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setPosition()
     */
    public function set_position(Point_Interface $position): Drawable_Interface
    {
        $this->start = $position;
        return $this;
    }
    /**
     * Return line width
     */
    public function width(): int
    {
        return $this->width;
    }
    /**
     * Set line width
     */
    public function set_width(int $width): self
    {
        $this->width = $width;
        return $this;
    }
    /**
     * Get starting point of line
     */
    public function start(): Point_Interface
    {
        return $this->start;
    }
    /**
     * get end point of line
     */
    public function end(): Point_Interface
    {
        return $this->end;
    }
    /**
     * Set starting point of line
     */
    public function set_start(Point_Interface $start): self
    {
        $this->start = $start;
        return $this;
    }
    /**
     * Set starting point of line by coordinates
     */
    public function from(int $x, int $y): self
    {
        $this->start()->set_x($x);
        $this->start()->set_y($y);
        return $this;
    }
    /**
     * Set end point of line by coordinates
     */
    public function to(int $x, int $y): self
    {
        $this->end()->set_x($x);
        $this->end()->set_y($y);
        return $this;
    }
    /**
     * Set end point of line
     */
    public function set_end(Point_Interface $end): self
    {
        $this->end = $end;
        return $this;
    }
}