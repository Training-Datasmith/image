<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry\Tools;

use Intervention\Image\Exceptions\Geometry_Exception;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Size_Interface;
class Rectangle_Resizer
{
    /**
     * @throws GeometryException
     */
    public function __construct(protected ?int $width = null, protected ?int $height = null)
    {
        if (is_int($width) && $width < 1) {
            throw new Geometry_Exception('The width you specify must be greater than or equal to 1.');
        }
        if (is_int($height) && $height < 1) {
            throw new Geometry_Exception('The height you specify must be greater than or equal to 1.');
        }
    }
    /**
     * Static factory method to create resizer with given target size
     *
     * @throws GeometryException
     */
    public static function to(mixed ...$arguments): self
    {
        return new self(...$arguments);
    }
    /**
     * Determine if resize has target width
     */
    protected function has_target_width(): bool
    {
        return is_int($this->width);
    }
    /**
     * Return target width of resizer if available
     */
    protected function get_target_width(): ?int
    {
        return $this->has_target_width() ? $this->width : null;
    }
    /**
     * Determine if resize has target height
     */
    protected function has_target_height(): bool
    {
        return is_int($this->height);
    }
    /**
     * Return target width of resizer if available
     */
    protected function get_target_height(): ?int
    {
        return $this->has_target_height() ? $this->height : null;
    }
    /**
     * Return target size object
     *
     * @throws GeometryException
     */
    protected function get_target_size(): Size_Interface
    {
        if (!$this->has_target_width() || !$this->has_target_height()) {
            throw new Geometry_Exception('Target size needs width and height.');
        }
        return new Rectangle($this->width, $this->height);
    }
    /**
     * Set target width of resizer
     */
    public function to_width(int $width): self
    {
        $this->width = $width;
        return $this;
    }
    /**
     * Set target height of resizer
     */
    public function to_height(int $height): self
    {
        $this->height = $height;
        return $this;
    }
    /**
     * Set target size to given size object
     */
    public function to_size(Size_Interface $size): self
    {
        $this->width = $size->width();
        $this->height = $size->height();
        return $this;
    }
    /**
     * Get proportinal width
     */
    protected function get_proportional_width(Size_Interface $size): int
    {
        if (!$this->has_target_height()) {
            return $size->width();
        }
        return max([1, (int) round($this->height * $size->aspect_ratio())]);
    }
    /**
     * Get proportinal height
     */
    protected function get_proportional_height(Size_Interface $size): int
    {
        if (!$this->has_target_width()) {
            return $size->height();
        }
        return max([1, (int) round($this->width / $size->aspect_ratio())]);
    }
    /**
     * Resize given size to target size of the resizer
     */
    public function resize(Size_Interface $size): Size_Interface
    {
        $resized = new Rectangle($size->width(), $size->height());
        if ($width = $this->get_target_width()) {
            $resized->set_width($width);
        }
        if ($height = $this->get_target_height()) {
            $resized->set_height($height);
        }
        return $resized;
    }
    /**
     * Resize given size to target size of the resizer but do not exceed original size
     */
    public function resize_down(Size_Interface $size): Size_Interface
    {
        $resized = new Rectangle($size->width(), $size->height());
        if ($width = $this->get_target_width()) {
            $resized->set_width(min($width, $size->width()));
        }
        if ($height = $this->get_target_height()) {
            $resized->set_height(min($height, $size->height()));
        }
        return $resized;
    }
    /**
     * Resize given size to target size proportinally
     */
    public function scale(Size_Interface $size): Size_Interface
    {
        $resized = new Rectangle($size->width(), $size->height());
        if ($this->has_target_width() && $this->has_target_height()) {
            $resized->set_width(min($this->get_proportional_width($size), $this->get_target_width()));
            $resized->set_height(min($this->get_proportional_height($size), $this->get_target_height()));
        } elseif ($this->has_target_width()) {
            $resized->set_width($this->get_target_width());
            $resized->set_height($this->get_proportional_height($size));
        } elseif ($this->has_target_height()) {
            $resized->set_width($this->get_proportional_width($size));
            $resized->set_height($this->get_target_height());
        }
        return $resized;
    }
    /**
     * Resize given size to target size proportinally but do not exceed original size
     */
    public function scale_down(Size_Interface $size): Size_Interface
    {
        $resized = new Rectangle($size->width(), $size->height());
        if ($this->has_target_width() && $this->has_target_height()) {
            $resized->set_width(min($this->get_proportional_width($size), $this->get_target_width(), $size->width()));
            $resized->set_height(min($this->get_proportional_height($size), $this->get_target_height(), $size->height()));
        } elseif ($this->has_target_width()) {
            $resized->set_width(min($this->get_target_width(), $size->width()));
            $resized->set_height(min($this->get_proportional_height($size), $size->height()));
        } elseif ($this->has_target_height()) {
            $resized->set_width(min($this->get_proportional_width($size), $size->width()));
            $resized->set_height(min($this->get_target_height(), $size->height()));
        }
        return $resized;
    }
    /**
     * Scale given size to cover target size
     *
     * @param SizeInterface $size Size to be resized
     * @throws GeometryException
     */
    public function cover(Size_Interface $size): Size_Interface
    {
        $resized = new Rectangle($size->width(), $size->height());
        // auto height
        $resized->set_width($this->get_target_width());
        $resized->set_height($this->get_proportional_height($size));
        if ($resized->fits_into($this->get_target_size())) {
            // auto width
            $resized->set_width($this->get_proportional_width($size));
            $resized->set_height($this->get_target_height());
        }
        return $resized;
    }
    /**
     * Scale given size to contain target size
     *
     * @param SizeInterface $size Size to be resized
     * @throws GeometryException
     */
    public function contain(Size_Interface $size): Size_Interface
    {
        $resized = new Rectangle($size->width(), $size->height());
        // auto height
        $resized->set_width($this->get_target_width());
        $resized->set_height($this->get_proportional_height($size));
        if (!$resized->fits_into($this->get_target_size())) {
            // auto width
            $resized->set_width($this->get_proportional_width($size));
            $resized->set_height($this->get_target_height());
        }
        return $resized;
    }
    /**
     * Scale given size to contain target size but prevent upsizing
     *
     * @param SizeInterface $size Size to be resized
     * @throws GeometryException
     */
    public function contain_down(Size_Interface $size): Size_Interface
    {
        $resized = new Rectangle($size->width(), $size->height());
        // auto height
        $resized->set_width(min($size->width(), $this->get_target_width()));
        $resized->set_height(min($size->height(), $this->get_proportional_height($size)));
        if (!$resized->fits_into($this->get_target_size())) {
            // auto width
            $resized->set_width(min($size->width(), $this->get_proportional_width($size)));
            $resized->set_height(min($size->height(), $this->get_target_height()));
        }
        return $resized;
    }
    /**
     * Crop target size out of given size at given position (i.e. move the pivot point)
     */
    public function crop(Size_Interface $size, string $position = 'top-left'): Size_Interface
    {
        return $this->resize($size)->align_pivot_to($size->move_pivot($position), $position);
    }
}