<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

interface Drawable_Interface
{
    /**
     * Position of the drawable object
     */
    public function position(): Point_Interface;
    /**
     * Set position of the drawable object
     */
    public function set_position(Point_Interface $position): self;
    /**
     * Set the background color of the drawable object
     */
    public function set_background_color(mixed $color): self;
    /**
     * Return background color of drawable object
     */
    public function background_color(): mixed;
    /**
     * Determine if a background color was set
     */
    public function has_background_color(): bool;
    /**
     * Set border color & size of the drawable object
     */
    public function set_border(mixed $color, int $size = 1): self;
    /**
     * Set border size of the drawable object
     */
    public function set_border_size(int $size): self;
    /**
     * Set border color of the drawable object
     */
    public function set_border_color(mixed $color): self;
    /**
     * Get border size
     */
    public function border_size(): int;
    /**
     * Get border color of drawable object
     */
    public function border_color(): mixed;
    /**
     * Determine if the drawable object has a border
     */
    public function has_border(): bool;
}