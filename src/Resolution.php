<?php

declare (strict_types=1);
namespace Intervention\Image;

use ArrayIterator;
use Intervention\Image\Interfaces\Resolution_Interface;
use IteratorAggregate;
use Stringable;
use Traversable;
/**
 * @implements IteratorAggregate<float>
 */
class Resolution implements Resolution_Interface, Stringable, IteratorAggregate
{
    public const PER_INCH = 1;
    public const PER_CM = 2;
    /**
     * Create new instance
     */
    public function __construct(protected float $x, protected float $y, protected int $per_unit = self::PER_INCH)
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
     * @see ResolutionInterface::x()
     */
    public function x(): float
    {
        return $this->x;
    }
    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::setX()
     */
    public function set_x(float $x): self
    {
        $this->x = $x;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::y()
     */
    public function y(): float
    {
        return $this->y;
    }
    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::setY()
     */
    public function set_y(float $y): self
    {
        $this->y = $y;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::setPerUnit()
     */
    protected function set_per_unit(int $per_unit): self
    {
        $this->per_unit = $per_unit;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::unit()
     */
    public function unit(): string
    {
        return match ($this->per_unit) {
            self::PER_CM => 'dpcm',
            default => 'dpi',
        };
    }
    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::perInch()
     */
    public function per_inch(): self
    {
        return match ($this->per_unit) {
            self::PER_CM => $this->set_per_unit(self::PER_INCH)->set_x($this->x * 2.54)->set_y($this->y * 2.54),
            default => $this,
        };
    }
    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::perCm()
     */
    public function per_cm(): self
    {
        return match ($this->per_unit) {
            self::PER_INCH => $this->set_per_unit(self::PER_CM)->set_x($this->x / 2.54)->set_y($this->y / 2.54),
            default => $this,
        };
    }
    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::toString()
     */
    public function to_string(): string
    {
        return sprintf('%1$.2f x %2$.2f %3$s', $this->x, $this->y, $this->unit());
    }
    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::__toString()
     */
    public function __toString(): string
    {
        return $this->to_string();
    }
}