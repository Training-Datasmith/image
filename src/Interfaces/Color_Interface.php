<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Exceptions\RuntimeException;
interface Color_Interface
{
    /**
     * Static color factory method that takes any supported color format
     * and returns a corresponding color object
     *
     * @throws RuntimeException
     */
    public static function create(mixed $input): self;
    /**
     * Return colorspace of current color
     */
    public function colorspace(): Colorspace_Interface;
    /**
     * Cast color object to string
     */
    public function to_string(): string;
    /**
     * Cast color object to array
     *
     * @return array<int>
     */
    public function to_array(): array;
    /**
     * Cast color object to hex encoded web color
     */
    public function to_hex(string $prefix = ''): string;
    /**
     * Return array of all color channels
     *
     * @return array<ColorChannelInterface>
     */
    public function channels(): array;
    /**
     * Return array of normalized color channel values
     *
     * @return array<float>
     */
    public function normalize(): array;
    /**
     * Retrieve the color channel by its classname
     *
     * @throws ColorException
     */
    public function channel(string $classname): Color_Channel_Interface;
    /**
     * Convert color to given colorspace
     */
    public function convert_to(string|Colorspace_Interface $colorspace): self;
    /**
     * Determine if the current color is gray
     */
    public function is_greyscale(): bool;
    /**
     * Determine if the current color is (semi) transparent
     */
    public function is_transparent(): bool;
    /**
     * Determine whether the current color is completely transparent
     */
    public function is_clear(): bool;
    /**
     * Cast color object to string
     */
    public function __toString(): string;
}