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
class Polygon implements IteratorAggregate, Countable, ArrayAccess, Drawable_Interface
{
    use Has_Border;
    use Has_Background_Color;
    /**
     * Create new polygon instance
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
    public function set_position(Point_Interface $position): self
    {
        $this->pivot = $position;
        return $this;
    }
    /**
     * Implement iteration through all points of polygon
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
     * Return first point of polygon
     */
    public function first(): ?Point_Interface
    {
        if ($point = reset($this->points)) {
            return $point;
        }
        return null;
    }
    /**
     * Return last point of polygon
     */
    public function last(): ?Point_Interface
    {
        if ($point = end($this->points)) {
            return $point;
        }
        return null;
    }
    /**
     * Return polygon's point count
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
     * Add given point to polygon
     */
    public function add_point(Point_Interface $point): self
    {
        $this->points[] = $point;
        return $this;
    }
    /**
     * Calculate total horizontal span of polygon
     */
    public function width(): int
    {
        return abs($this->most_left_point()->x() - $this->most_right_point()->x());
    }
    /**
     * Calculate total vertical span of polygon
     */
    public function height(): int
    {
        return abs($this->most_bottom_point()->y() - $this->most_top_point()->y());
    }
    /**
     * Return most left point of all points in polygon
     */
    public function most_left_point(): Point_Interface
    {
        $points = $this->points;
        usort($points, fn(Point_Interface $a, Point_Interface $b): int => $a->x() <=> $b->x());
        return $points[0];
    }
    /**
     * Return most right point in polygon
     */
    public function most_right_point(): Point_Interface
    {
        $points = $this->points;
        usort($points, fn(Point_Interface $a, Point_Interface $b): int => $b->x() <=> $a->x());
        return $points[0];
    }
    /**
     * Return most top point in polygon
     */
    public function most_top_point(): Point_Interface
    {
        $points = $this->points;
        usort($points, fn(Point_Interface $a, Point_Interface $b): int => $b->y() <=> $a->y());
        return $points[0];
    }
    /**
     * Return most bottom point in polygon
     */
    public function most_bottom_point(): Point_Interface
    {
        $points = $this->points;
        usort($points, fn(Point_Interface $a, Point_Interface $b): int => $a->y() <=> $b->y());
        return $points[0];
    }
    /**
     * Return point in absolute center of the polygon
     */
    public function center_point(): Point_Interface
    {
        return new Point($this->most_right_point()->x() - intval(round($this->width() / 2)), $this->most_top_point()->y() - intval(round($this->height() / 2)));
    }
    /**
     * Align all points of polygon horizontally to given position around pivot point
     */
    public function align(string $position): self
    {
        switch (strtolower($position)) {
            case 'center':
            case 'middle':
                $diff = $this->center_point()->x() - $this->pivot()->x();
                break;
            case 'right':
                $diff = $this->most_right_point()->x() - $this->pivot()->x();
                break;
            default:
            case 'left':
                $diff = $this->most_left_point()->x() - $this->pivot()->x();
                break;
        }
        foreach ($this->points as $point) {
            $point->set_x(intval($point->x() - $diff));
        }
        return $this;
    }
    /**
     * Align all points of polygon vertically to given position around pivot point
     */
    public function valign(string $position): self
    {
        switch (strtolower($position)) {
            case 'center':
            case 'middle':
                $diff = $this->center_point()->y() - $this->pivot()->y();
                break;
            case 'top':
                $diff = $this->most_top_point()->y() - $this->pivot()->y() - $this->height();
                break;
            default:
            case 'bottom':
                $diff = $this->most_bottom_point()->y() - $this->pivot()->y() + $this->height();
                break;
        }
        foreach ($this->points as $point) {
            $point->set_y(intval($point->y() - $diff));
        }
        return $this;
    }
    /**
     * Rotate points of polygon around pivot point with given angle
     */
    public function rotate(float $angle): self
    {
        $sin = sin(deg2rad($angle));
        $cos = cos(deg2rad($angle));
        foreach ($this->points as $point) {
            // translate point to pivot
            $point->set_x(intval($point->x() - $this->pivot()->x()));
            $point->set_y(intval($point->y() - $this->pivot()->y()));
            // rotate point
            $x = $point->x() * $cos - $point->y() * $sin;
            $y = $point->x() * $sin + $point->y() * $cos;
            // translate point back
            $point->set_x(intval($x + $this->pivot()->x()));
            $point->set_y(intval($y + $this->pivot()->y()));
        }
        return $this;
    }
    /**
     * Move all points by given amount on the x-axis
     */
    public function move_points_x(int $amount): self
    {
        foreach ($this->points as $point) {
            $point->move_x($amount);
        }
        return $this;
    }
    /**
     * Move all points by given amount on the y-axis
     */
    public function move_points_y(int $amount): self
    {
        foreach ($this->points as $point) {
            $point->move_y($amount);
        }
        return $this;
    }
    /**
     * Return array of all x/y values of all points of polygon
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