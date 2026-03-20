<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\Font_Exception;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Font_Interface;
use Intervention\Image\Interfaces\Font_Processor_Interface;
use Intervention\Image\Interfaces\Point_Interface;
use Intervention\Image\Typography\Line;
use Intervention\Image\Typography\Text_Block;
abstract class Abstract_Font_Processor implements Font_Processor_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::textBlock()
     */
    public function text_block(string $text, Font_Interface $font, Point_Interface $position): Text_Block
    {
        $lines = $this->wrap_text_block(new Text_Block($text), $font);
        $pivot = $this->build_pivot($lines, $font, $position);
        $leading = $this->leading($font);
        $block_width = $this->box_size((string) $lines->longest_line(), $font)->width();
        $x = $pivot->x();
        $y = $font->has_filename() ? $pivot->y() + $this->cap_height($font) : $pivot->y();
        $x_adjustment = 0;
        // adjust line positions according to alignment
        foreach ($lines as $line) {
            $line_box_size = $this->box_size((string) $line, $font);
            $line_width = $line_box_size->width() + $line_box_size->pivot()->x();
            $x_adjustment = $font->alignment() === 'left' ? 0 : $block_width - $line_width;
            $x_adjustment = $font->alignment() === 'right' ? intval(round($x_adjustment)) : $x_adjustment;
            $x_adjustment = $font->alignment() === 'center' ? intval(round($x_adjustment / 2)) : $x_adjustment;
            $position = new Point($x + $x_adjustment, $y);
            $position->rotate($font->angle(), $pivot);
            $line->set_position($position);
            $y += $leading;
        }
        return $lines;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::nativeFontSize()
     */
    public function native_font_size(Font_Interface $font): float
    {
        return $font->size();
    }
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::typographicalSize()
     */
    public function typographical_size(Font_Interface $font): int
    {
        return $this->box_size('Hy', $font)->height();
    }
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::capHeight()
     */
    public function cap_height(Font_Interface $font): int
    {
        return $this->box_size('T', $font)->height();
    }
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::leading()
     */
    public function leading(Font_Interface $font): int
    {
        return intval(round($this->typographical_size($font) * $font->line_height()));
    }
    /**
     * Reformat a text block by wrapping each line before the given maximum width
     *
     * @throws FontException
     */
    protected function wrap_text_block(Text_Block $block, Font_Interface $font): Text_Block
    {
        $new_lines = [];
        foreach ($block as $line) {
            foreach ($this->wrap_line($line, $font) as $new_line) {
                $new_lines[] = $new_line;
            }
        }
        return $block->set_lines($new_lines);
    }
    /**
     * Check if a line exceeds the given maximum width and wrap it if necessary.
     * The output will be an array of formatted lines that are all within the
     * maximum width.
     *
     * @throws FontException
     * @return array<Line>
     */
    protected function wrap_line(Line $line, Font_Interface $font): array
    {
        // no wrap width - no wrapping
        if (is_null($font->wrap_width())) {
            return [$line];
        }
        $wrapped = [];
        $formatted_line = new Line();
        foreach ($line as $word) {
            // calculate width of newly formatted line
            $line_width = $this->box_size(match ($formatted_line->count()) {
                0 => $word,
                default => $formatted_line . ' ' . $word,
            }, $font)->width();
            // decide if word fits on current line or a new line must be created
            if ($line->count() === 1 || $line_width <= $font->wrap_width()) {
                $formatted_line->add($word);
            } else {
                if ($formatted_line->count() !== 0) {
                    $wrapped[] = $formatted_line;
                }
                $formatted_line = new Line($word);
            }
        }
        $wrapped[] = $formatted_line;
        return $wrapped;
    }
    /**
     * Build pivot point of textblock according to the font settings and based on given position
     *
     * @throws FontException
     */
    protected function build_pivot(Text_Block $block, Font_Interface $font, Point_Interface $position): Point_Interface
    {
        // bounding box
        $box = new Rectangle($this->box_size((string) $block->longest_line(), $font)->width(), $this->leading($font) * ($block->count() - 1) + $this->cap_height($font));
        // set position
        $box->set_pivot($position);
        // alignment
        $box->align($font->alignment());
        $box->valign($font->valignment());
        $box->rotate($font->angle());
        return $box->last();
    }
}