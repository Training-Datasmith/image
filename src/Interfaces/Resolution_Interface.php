<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

interface Resolution_Interface
{
    /**
     * Return resolution of x-axis
     */
    public function x(): float;
    /**
     * Set resolution on x-axis
     */
    public function set_x(float $x): self;
    /**
     * Return resolution on y-axis
     */
    public function y(): float;
    /**
     * Set resolution on y-axis
     */
    public function set_y(float $y): self;
    /**
     * Convert the resolution to DPI
     */
    public function per_inch(): self;
    /**
     * Convert the resolution to DPCM
     */
    public function per_cm(): self;
    /**
     * Return string representation of unit
     */
    public function unit(): string;
    /**
     * Transform object to string
     */
    public function to_string(): string;
    /**
     * Cast object to string
     */
    public function __toString(): string;
}