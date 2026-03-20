<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\Font_Exception;
use Intervention\Image\Typography\Text_Block;
interface Font_Processor_Interface
{
    /**
     * Calculate size of bounding box of given text in conjunction with the given font
     *
     * @throws FontException
     */
    public function box_size(string $text, Font_Interface $font): Size_Interface;
    /**
     * Build TextBlock object from text string and align every line according
     * to text modifier's font object and position.
     *
     * @throws FontException
     */
    public function text_block(string $text, Font_Interface $font, Point_Interface $position): Text_Block;
    /**
     * Calculate the actual font size to pass at the driver level
     */
    public function native_font_size(Font_Interface $font): float;
    /**
     * Calculate the typographical font size in pixels
     *
     * @throws FontException
     */
    public function typographical_size(Font_Interface $font): int;
    /**
     * Calculates typographical cap height
     *
     * @throws FontException
     */
    public function cap_height(Font_Interface $font): int;
    /**
     * Calculates typographical leading size
     *
     * @throws FontException
     */
    public function leading(Font_Interface $font): int;
}