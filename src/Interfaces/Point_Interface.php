<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

interface Point_Interface
{
    /**
     * Return x position
     */
    public function x(): int;
    /**
     * Return y position
     */
    public function y(): int;
    /**
     * Set x position
     */
    public function set_x(int $x): self;
    /**
     * Set y position
     */
    public function set_y(int $y): self;
    /**
     * Move X coordinate
     */
    public function move_x(int $value): self;
    /**
     * Move Y coordinate
     */
    public function move_y(int $value): self;
    /**
     * Move position of current point by given coordinates
     */
    public function move(int $x, int $y): self;
    /**
     * Set position of point
     */
    public function set_position(int $x, int $y): self;
    /**
     * Rotate point counter clock wise around given pivot point
     */
    public function rotate(float $angle, self $pivot): self;
}