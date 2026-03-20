<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick_Draw;
use Imagick_Draw_Exception;
use Imagick_Exception;
use Intervention\Image\Drivers\Imagick\Font_Processor;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Exceptions\Font_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\Font_Interface;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Point_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Text_Modifier as GenericTextModifier;
use Intervention\Image\Typography\Line;
class Text_Modifier extends Generic_Text_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        $lines = $this->processor()->text_block($this->text, $this->font, $this->position);
        $draw_text = $this->imagick_draw_text($image, $this->font);
        $draw_stroke = $this->imagick_draw_stroke($image, $this->font);
        foreach ($image as $frame) {
            foreach ($lines as $line) {
                foreach ($this->stroke_offsets($this->font) as $offset) {
                    // Draw the stroke outline under the actual text
                    $this->maybe_draw_textline($frame, $line, $draw_stroke, $offset);
                }
                // Draw the actual text
                $this->maybe_draw_textline($frame, $line, $draw_text);
            }
        }
        return $image;
    }
    /**
     * Create an ImagickDraw object to draw text on the image
     *
     * @throws RuntimeException
     * @throws ColorException
     * @throws FontException
     * @throws ImagickDrawException
     * @throws ImagickException
     */
    private function imagick_draw_text(Image_Interface $image, Font_Interface $font): Imagick_Draw
    {
        $color = $this->driver()->handle_input($font->color());
        if ($font->has_stroke_effect() && $color->is_transparent()) {
            throw new Color_Exception('The text color must be fully opaque when using the stroke effect.');
        }
        $color = $this->driver()->color_processor($image->colorspace())->color_to_native($color);
        return $this->processor()->to_imagick_draw($font, $color);
    }
    /**
     * Create a ImagickDraw object to draw the outline stroke effect on the Image
     *
     * @throws RuntimeException
     * @throws ColorException
     * @throws FontException
     * @throws ImagickDrawException
     * @throws ImagickException
     */
    private function imagick_draw_stroke(Image_Interface $image, Font_Interface $font): ?Imagick_Draw
    {
        if (!$font->has_stroke_effect()) {
            return null;
        }
        $color = $this->driver()->handle_input($font->stroke_color());
        if ($color->is_transparent()) {
            throw new Color_Exception('The stroke color must be fully opaque.');
        }
        $color = $this->driver()->color_processor($image->colorspace())->color_to_native($color);
        return $this->processor()->to_imagick_draw($font, $color);
    }
    /**
     * Maybe draw given line of text on frame instance depending on given
     * ImageDraw instance. Optionally move line position by given offset.
     */
    private function maybe_draw_textline(Frame_Interface $frame, Line $textline, ?Imagick_Draw $draw = null, Point_Interface $offset = new Point()): void
    {
        if ($draw instanceof Imagick_Draw) {
            $frame->native()->annotate_image($draw, $textline->position()->x() + $offset->x(), $textline->position()->y() + $offset->y(), $this->font->angle(), (string) $textline);
        }
    }
    /**
     * Return imagick font processor
     *
     * @throws FontException
     */
    private function processor(): Font_Processor
    {
        $processor = $this->driver()->font_processor();
        if (!$processor instanceof Font_Processor) {
            throw new Font_Exception('Font processor does not match the driver.');
        }
        return $processor;
    }
}