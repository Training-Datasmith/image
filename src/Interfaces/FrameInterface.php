<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;
interface Frame_Interface
{
    /**
     * Return image data of frame in driver specific format
     */
    public function native(): mixed;
    /**
     * Set image data of drame in driver specific format
     */
    public function set_native(mixed $native): self;
    /**
     * Transform frame into an image
     *
     * @throws RuntimeException
     */
    public function to_image(Driver_Interface $driver): Image_Interface;
    /**
     * Get image size of current frame
     */
    public function size(): Size_Interface;
    /**
     * Return animation delay of current frame in seconds
     */
    public function delay(): float;
    /**
     * Set animation frame delay in seoncds
     */
    public function set_delay(float $delay): self;
    /**
     * Get disposal method of current frame
     */
    public function dispose(): int;
    /**
     * Set disposal method of current frame
     */
    public function set_dispose(int $dispose): self;
    /**
     * Set pixel offset of current frame
     */
    public function set_offset(int $left, int $top): self;
    /**
     * Get left offset in pixels
     */
    public function offset_left(): int;
    /**
     * Set left pixel offset for current frame
     */
    public function set_offset_left(int $offset): self;
    /**
     * Get top pixel offset of current frame
     */
    public function offset_top(): int;
    /**
     * Set top pixel offset of current frame
     */
    public function set_offset_top(int $offset): self;
}