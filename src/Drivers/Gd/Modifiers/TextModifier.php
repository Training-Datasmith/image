<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Exceptions\Font_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Text_Modifier as GenericTextModifier;
class Text_Modifier extends Generic_Text_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $font_processor = $this->driver()->font_processor();
        $lines = $font_processor->text_block($this->text, $this->font, $this->position);
        // decode text colors
        $text_color = $this->gd_text_color($image);
        $stroke_color = $this->gd_stroke_color($image);
        // build full path to font file to make sure to pass absolute path to imageftbbox()
        // because of issues with different GD version behaving differently when passing
        // relative paths to imagettftext()
        $font_path = $this->font->has_filename() ? realpath($this->font->filename()) : false;
        if ($this->font->has_filename() && $font_path === false) {
            throw new Font_Exception('Font file ' . $this->font->filename() . ' does not exist.');
        }
        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            if ($this->font->has_filename()) {
                foreach ($lines as $line) {
                    foreach ($this->stroke_offsets($this->font) as $offset) {
                        imagettftext(image: $frame->native(), size: $font_processor->native_font_size($this->font), angle: $this->font->angle() * -1, x: $line->position()->x() + $offset->x(), y: $line->position()->y() + $offset->y(), color: $stroke_color, font_filename: $font_path, text: (string) $line);
                    }
                    imagettftext(image: $frame->native(), size: $font_processor->native_font_size($this->font), angle: $this->font->angle() * -1, x: $line->position()->x(), y: $line->position()->y(), color: $text_color, font_filename: $font_path, text: (string) $line);
                }
            } else {
                foreach ($lines as $line) {
                    foreach ($this->stroke_offsets($this->font) as $offset) {
                        imagestring($frame->native(), $this->gd_font(), $line->position()->x() + $offset->x(), $line->position()->y() + $offset->y(), (string) $line, $stroke_color);
                    }
                    imagestring($frame->native(), $this->gd_font(), $line->position()->x(), $line->position()->y(), (string) $line, $text_color);
                }
            }
        }
        return $image;
    }
    /**
     * Decode text color in GD compatible format
     *
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function gd_text_color(Image_Interface $image): int
    {
        return $this->driver()->color_processor($image->colorspace())->color_to_native(parent::text_color());
    }
    /**
     * Decode color for stroke (outline) effect in GD compatible format
     *
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function gd_stroke_color(Image_Interface $image): int
    {
        if (!$this->font->has_stroke_effect()) {
            return 0;
        }
        $color = parent::stroke_color();
        if ($color->is_transparent()) {
            throw new Color_Exception('The stroke color must be fully opaque.');
        }
        return $this->driver()->color_processor($image->colorspace())->color_to_native($color);
    }
    /**
     * Return GD's internal font size (if no ttf file is set)
     */
    private function gd_font(): int
    {
        if (is_numeric($this->font->filename())) {
            return intval($this->font->filename());
        }
        return 1;
    }
}