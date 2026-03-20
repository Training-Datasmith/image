<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\Font_Exception;
interface Font_Interface
{
    /**
     * Set color of font
     */
    public function set_color(mixed $color): self;
    /**
     * Get color of font
     */
    public function color(): mixed;
    /**
     * Set stroke color of font
     */
    public function set_stroke_color(mixed $color): self;
    /**
     * Get stroke color of font
     */
    public function stroke_color(): mixed;
    /**
        /**
    * Set stroke width of font
    *
    * @throws FontException
    */
    public function set_stroke_width(int $width): self;
    /**
     * Get stroke width of font
     */
    public function stroke_width(): int;
    /**
     * Determine if the font is drawn with outline stroke effect
     */
    public function has_stroke_effect(): bool;
    /**
     * Set font size
     */
    public function set_size(float $size): self;
    /**
     * Get font size
     */
    public function size(): float;
    /**
     * Set rotation angle of font
     */
    public function set_angle(float $angle): self;
    /**
     * Get rotation angle of font
     */
    public function angle(): float;
    /**
     * Set font filename
     */
    public function set_filename(string $filename): self;
    /**
     * Get font filename
     */
    public function filename(): ?string;
    /**
     * Determine if font has a corresponding filename
     */
    public function has_filename(): bool;
    /**
     * Set horizontal alignment of font
     */
    public function set_alignment(string $align): self;
    /**
     * Get horizontal alignment of font
     */
    public function alignment(): string;
    /**
     * Set vertical alignment of font
     */
    public function set_valignment(string $align): self;
    /**
     * Get vertical alignment of font
     */
    public function valignment(): string;
    /**
     * Set typographical line height
     */
    public function set_line_height(float $value): self;
    /**
     * Get line height of font
     */
    public function line_height(): float;
    /**
     *  Set the wrap width with which the text is rendered
     */
    public function set_wrap_width(?int $width): self;
    /**
     * Get wrap width with which the text is rendered
     */
    public function wrap_width(): ?int;
}