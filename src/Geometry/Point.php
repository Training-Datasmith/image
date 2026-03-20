<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry;

use ArrayIterator;
use Intervention\Image\Interfaces\Point_Interface;
use IteratorAggregate;
use Traversable;
/**
 * @implements IteratorAggregate<int>
 */
class Point implements Point_Interface, IteratorAggregate
{
    /**
     * Create new point instance
     */
    public function __construct(protected int $x = 0, protected int $y = 0)
    {
    }
    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator([$this->x, $this->y]);
    }
    /**
     * {@inheritdoc}
     *
     * @see PointInterface::setX()
     */
    public function set_x(int $x): self
    {
        $this->x = $x;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see PointInterface::x()
     */
    public function x(): int
    {
        return $this->x;
    }
    /**
     * {@inheritdoc}
     *
     * @see PointInterface::setY()
     */
    public function set_y(int $y): self
    {
        $this->y = $y;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see PointInterface::y()
     */
    public function y(): int
    {
        return $this->y;
    }
    /**
     * {@inheritdoc}
     *
     * @see PointInterface::moveX()
     */
    public function move_x(int $value): self
    {
        $this->x += $value;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see PointInterface::moveY()
     */
    public function move_y(int $value): self
    {
        $this->y += $value;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see PointInterface::move()
     */
    public function move(int $x, int $y): self
    {
        return $this->move_x($x)->move_y($y);
    }
    /**
     * {@inheritdoc}
     *
     * @see PointInterface::setPosition()
     */
    public function set_position(int $x, int $y): self
    {
        $this->set_x($x);
        $this->set_y($y);
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see PointInterface::rotate()
     */
    public function rotate(float $angle, Point_Interface $pivot): self
    {
        $sin = round(sin(deg2rad($angle)), 6);
        $cos = round(cos(deg2rad($angle)), 6);
        return $this->set_position(intval($cos * ($this->x() - $pivot->x()) - $sin * ($this->y() - $pivot->y()) + $pivot->x()), intval($sin * ($this->x() - $pivot->x()) + $cos * ($this->y() - $pivot->y()) + $pivot->y()));
    }
}