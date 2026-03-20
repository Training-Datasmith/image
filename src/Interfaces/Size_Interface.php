<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\Geometry_Exception;
interface Size_Interface
{
    /**
     * Get width
     */
    public function width(): int;
    /**
     * Get height
     */
    public function height(): int;
    /**
     * Get pivot point
     */
    public function pivot(): Point_Interface;
    /**
     * Set width
     */
    public function set_width(int $width): self;
    /**
     * Set height
     */
    public function set_height(int $height): self;
    /**
     * Set pivot point
     */
    public function set_pivot(Point_Interface $pivot): self;
    /**
     * Calculate aspect ratio of the current size
     */
    public function aspect_ratio(): float;
    /**
     * Determine if current size fits into given size
     */
    public function fits_into(self $size): bool;
    /**
     * Determine if size is in landscape format
     */
    public function is_landscape(): bool;
    /**
     * Determine if size is in portrait format
     */
    public function is_portrait(): bool;
    /**
     * Move pivot to given position in size
     */
    public function move_pivot(string $position, int $offset_x = 0, int $offset_y = 0): self;
    /**
     * Align pivot of current object to given position
     */
    public function align_pivot_to(self $size, string $position): self;
    /**
     * Calculate the relative position to another Size
     * based on the pivot point settings of both sizes.
     */
    public function relative_position_to(self $size): Point_Interface;
    /**
     * @see ImageInterface::resize()
     *
     * @throws GeometryException
     */
    public function resize(?int $width = null, ?int $height = null): self;
    /**
     * @see ImageInterface::resizeDown()
     *
     * @throws GeometryException
     */
    public function resize_down(?int $width = null, ?int $height = null): self;
    /**
     * @see ImageInterface::scale()
     *
     * @throws GeometryException
     */
    public function scale(?int $width = null, ?int $height = null): self;
    /**
     * @see ImageInterface::scaleDown()
     *
     * @throws GeometryException
     */
    public function scale_down(?int $width = null, ?int $height = null): self;
    /**
     * @see ImageInterface::cover()
     *
     * @throws GeometryException
     */
    public function cover(int $width, int $height): self;
    /**
     * @see ImageInterface::contain()
     *
     * @throws GeometryException
     */
    public function contain(int $width, int $height): self;
    /**
     * @throws GeometryException
     */
    public function contain_max(int $width, int $height): self;
}