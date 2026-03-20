<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\Point_Interface;
class Circle extends Ellipse
{
    /**
     * Create new Circle instance
     */
    public function __construct(int $diameter, Point_Interface $pivot = new Point())
    {
        parent::__construct($diameter, $diameter, $pivot);
    }
    /**
     * Set diameter of circle
     */
    public function set_diameter(int $diameter): self
    {
        $this->set_width($diameter);
        $this->set_height($diameter);
        return $this;
    }
    /**
     * Get diameter of circle
     */
    public function diameter(): int
    {
        return $this->width();
    }
    /**
     * Set radius of circle
     */
    public function set_radius(int $radius): self
    {
        return $this->set_diameter(intval($radius * 2));
    }
    /**
     * Get radius of circle
     */
    public function radius(): int
    {
        return intval(round($this->diameter() / 2));
    }
}