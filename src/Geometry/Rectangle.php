<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry;

use Intervention\Image\Exceptions\Geometry_Exception;
use Intervention\Image\Geometry\Tools\Rectangle_Resizer;
use Intervention\Image\Interfaces\Point_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Rectangle extends Polygon implements Size_Interface
{
    /**
     * Create new rectangle instance
     */
    public function __construct(int $width, int $height, protected Point_Interface $pivot = new Point())
    {
        $this->add_point(new Point($this->pivot->x(), $this->pivot->y()));
        $this->add_point(new Point($this->pivot->x() + $width, $this->pivot->y()));
        $this->add_point(new Point($this->pivot->x() + $width, $this->pivot->y() - $height));
        $this->add_point(new Point($this->pivot->x(), $this->pivot->y() - $height));
    }
    /**
     * Set size of rectangle
     */
    public function set_size(int $width, int $height): self
    {
        return $this->set_width($width)->set_height($height);
    }
    /**
     * Set width of rectangle
     */
    public function set_width(int $width): self
    {
        $this[1]->set_x($this[0]->x() + $width);
        $this[2]->set_x($this[3]->x() + $width);
        return $this;
    }
    /**
     * Set height of rectangle
     */
    public function set_height(int $height): self
    {
        $this[2]->set_y($this[1]->y() + $height);
        $this[3]->set_y($this[0]->y() + $height);
        return $this;
    }
    /**
     * Return pivot point of rectangle
     */
    public function pivot(): Point_Interface
    {
        return $this->pivot;
    }
    /**
     * Set pivot point of rectangle
     */
    public function set_pivot(Point_Interface $pivot): self
    {
        $this->pivot = $pivot;
        return $this;
    }
    /**
     * Move pivot to the given position in the rectangle and adjust the new
     * position by given offset values.
     */
    public function move_pivot(string $position, int $offset_x = 0, int $offset_y = 0): self
    {
        switch (strtolower($position)) {
            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $x = intval(round($this->width() / 2)) + $offset_x;
                $y = $offset_y;
                break;
            case 'top-right':
            case 'right-top':
                $x = $this->width() - $offset_x;
                $y = $offset_y;
                break;
            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $x = $offset_x;
                $y = intval(round($this->height() / 2)) + $offset_y;
                break;
            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $x = $this->width() - $offset_x;
                $y = intval(round($this->height() / 2)) + $offset_y;
                break;
            case 'bottom-left':
            case 'left-bottom':
                $x = $offset_x;
                $y = $this->height() - $offset_y;
                break;
            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $x = intval(round($this->width() / 2)) + $offset_x;
                $y = $this->height() - $offset_y;
                break;
            case 'bottom-right':
            case 'right-bottom':
                $x = $this->width() - $offset_x;
                $y = $this->height() - $offset_y;
                break;
            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $x = intval(round($this->width() / 2)) + $offset_x;
                $y = intval(round($this->height() / 2)) + $offset_y;
                break;
            default:
            case 'top-left':
            case 'left-top':
                $x = $offset_x;
                $y = $offset_y;
                break;
        }
        $this->pivot->set_position($x, $y);
        return $this;
    }
    /**
     * Align pivot relative to given size at given position
     */
    public function align_pivot_to(Size_Interface $size, string $position): self
    {
        $reference = new self($size->width(), $size->height());
        $reference->move_pivot($position);
        $this->move_pivot($position)->set_pivot($reference->relative_position_to($this));
        return $this;
    }
    /**
     * Return relative position to given rectangle
     */
    public function relative_position_to(Size_Interface $rectangle): Point_Interface
    {
        return new Point($this->pivot()->x() - $rectangle->pivot()->x(), $this->pivot()->y() - $rectangle->pivot()->y());
    }
    /**
     * Return aspect ration of rectangle
     */
    public function aspect_ratio(): float
    {
        return $this->width() / $this->height();
    }
    /**
     * Determine if rectangle fits into given rectangle
     */
    public function fits_into(Size_Interface $size): bool
    {
        if ($this->width() > $size->width()) {
            return false;
        }
        if ($this->height() > $size->height()) {
            return false;
        }
        return true;
    }
    /**
     * Determine if rectangle has landscape format
     */
    public function is_landscape(): bool
    {
        return $this->width() > $this->height();
    }
    /**
     * Determine if rectangle has landscape format
     */
    public function is_portrait(): bool
    {
        return $this->width() < $this->height();
    }
    /**
     * Return most top left point of rectangle
     */
    public function top_left_point(): Point_Interface
    {
        return $this->points[0];
    }
    /**
     * Return bottom right point of rectangle
     */
    public function bottom_right_point(): Point_Interface
    {
        return $this->points[2];
    }
    /**
     * @see SizeInterface::resize()
     *
     * @throws GeometryException
     */
    public function resize(?int $width = null, ?int $height = null): Size_Interface
    {
        return $this->resizer($width, $height)->resize($this);
    }
    /**
     * @see SizeInterface::resizeDown()
     *
     * @throws GeometryException
     */
    public function resize_down(?int $width = null, ?int $height = null): Size_Interface
    {
        return $this->resizer($width, $height)->resize_down($this);
    }
    /**
     * @see SizeInterface::scale()
     *
     * @throws GeometryException
     */
    public function scale(?int $width = null, ?int $height = null): Size_Interface
    {
        return $this->resizer($width, $height)->scale($this);
    }
    /**
     * @see SizeInterface::scaleDown()
     *
     * @throws GeometryException
     */
    public function scale_down(?int $width = null, ?int $height = null): Size_Interface
    {
        return $this->resizer($width, $height)->scale_down($this);
    }
    /**
     * @see SizeInterface::cover()
     *
     * @throws GeometryException
     */
    public function cover(int $width, int $height): Size_Interface
    {
        return $this->resizer($width, $height)->cover($this);
    }
    /**
     * @see SizeInterface::contain()
     *
     * @throws GeometryException
     */
    public function contain(int $width, int $height): Size_Interface
    {
        return $this->resizer($width, $height)->contain($this);
    }
    /**
     * @see SizeInterface::containMax()
     *
     * @throws GeometryException
     */
    public function contain_max(int $width, int $height): Size_Interface
    {
        return $this->resizer($width, $height)->contain_down($this);
    }
    /**
     * Create resizer instance with given target size
     *
     * @throws GeometryException
     */
    protected function resizer(?int $width = null, ?int $height = null): Rectangle_Resizer
    {
        return new Rectangle_Resizer($width, $height);
    }
    /**
     * Show debug info for the current rectangle
     *
     * @return array<string, int|object>
     */
    public function __debugInfo(): array
    {
        return ['width' => $this->width(), 'height' => $this->height(), 'pivot' => $this->pivot];
    }
}