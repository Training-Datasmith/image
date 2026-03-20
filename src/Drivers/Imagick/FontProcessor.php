<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use Imagick_Draw;
use Imagick_Draw_Exception;
use Imagick_Exception;
use Imagick_Pixel;
use Intervention\Image\Drivers\Abstract_Font_Processor;
use Intervention\Image\Exceptions\Font_Exception;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Font_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Font_Processor extends Abstract_Font_Processor
{
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::boxSize()
     */
    public function box_size(string $text, Font_Interface $font): Size_Interface
    {
        // no text - no box size
        if (mb_strlen($text) === 0) {
            return new Rectangle(0, 0);
        }
        $draw = $this->to_imagick_draw($font);
        $dimensions = (new Imagick())->query_font_metrics($draw, $text);
        return new Rectangle(intval(round($dimensions['textWidth'])), intval(round($dimensions['ascender'] + $dimensions['descender'])));
    }
    /**
     * Imagick::annotateImage() needs an ImagickDraw object - this method takes
     * the font object as the base and adds an optional passed color to the new
     * ImagickDraw object.
     *
     * @throws FontException
     * @throws ImagickDrawException
     * @throws ImagickException
     */
    public function to_imagick_draw(Font_Interface $font, ?Imagick_Pixel $color = null): Imagick_Draw
    {
        if (!$font->has_filename()) {
            throw new Font_Exception('No font file specified.');
        }
        $font_path = realpath((string) $font->filename());
        if ($font_path === false) {
            throw new Font_Exception('Font file ' . $font->filename() . ' does not exist.');
        }
        $draw = new Imagick_Draw();
        $draw->set_stroke_antialias(true);
        $draw->set_text_antialias(true);
        $draw->set_font($font_path);
        $draw->set_font_size($this->native_font_size($font));
        $draw->set_text_alignment(Imagick::ALIGN_LEFT);
        if ($color instanceof Imagick_Pixel) {
            $draw->set_fill_color($color);
        }
        return $draw;
    }
}