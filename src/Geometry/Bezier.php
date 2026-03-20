<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Intervention\Image\Geometry\Traits\Has_Background_Color;
use Intervention\Image\Geometry\Traits\Has_Border;
use Intervention\Image\Interfaces\Drawable_Interface;
use Intervention\Image\Interfaces\Point_Interface;
use IteratorAggregate;
use Traversable;
/**
 * @implements IteratorAggregate<PointInterface>
 * @implements ArrayAccess<int, PointInterface>
 */
class Bezier implements IteratorAggregate, Countable, ArrayAccess, Drawable_Interface
{
    use Has_Border;
    use Has_Background_Color;
    /**
     * Create new bezier instance
     *
     * @param array<PointInterface> $points
     */
    public function __construct(protected array $points = [], protected Point_Interface $pivot = new Point())
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
    public function set_position(Point_Interface $position): Drawable_Interface
    {
        $this->pivot = $position;
        return $this;
    }
    /**
     * Implement iteration through all points of bezier
     *
     * @return Traversable<PointInterface>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->points);
    }
    /**
     * Return current pivot point
     */
    public function pivot(): Point_Interface
    {
        return $this->pivot;
    }
    /**
     * Change pivot point to given point
     */
    public function set_pivot(Point_Interface $pivot): self
    {
        $this->pivot = $pivot;
        return $this;
    }
    /**
     * Return first control point of bezier
     */
    public function first(): ?Point_Interface
    {
        if ($point = reset($this->points)) {
            return $point;
        }
        return null;
    }
    /**
     * Return second control point of bezier
     */
    public function second(): ?Point_Interface
    {
        if (array_key_exists(1, $this->points)) {
            return $this->points[1];
        }
        return null;
    }
    /**
     * Return third control point of bezier
     */
    public function third(): ?Point_Interface
    {
        if (array_key_exists(2, $this->points)) {
            return $this->points[2];
        }
        return null;
    }
    /**
     * Return last control point of bezier
     */
    public function last(): ?Point_Interface
    {
        if ($point = end($this->points)) {
            return $point;
        }
        return null;
    }
    /**
     * Return bezier's point count
     */
    public function count(): int
    {
        return count($this->points);
    }
    /**
     * Determine if point exists at given offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->points);
    }
    /**
     * Return point at given offset
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->points[$offset];
    }
    /**
     * Set point at given offset
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->points[$offset] = $value;
    }
    /**
     * Unset offset at given offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->points[$offset]);
    }
    /**
     * Add given point to bezier
     */
    public function add_point(Point_Interface $point): self
    {
        $this->points[] = $point;
        return $this;
    }
    /**
     * Return array of all x/y values of all points of bezier
     *
     * @return array<int>
     */
    public function to_array(): array
    {
        $coordinates = [];
        foreach ($this->points as $point) {
            $coordinates[] = $point->x();
            $coordinates[] = $point->y();
        }
        return $coordinates;
    }
}