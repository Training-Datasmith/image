<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Font_Interface;
use Intervention\Image\Interfaces\Point_Interface;
class Text_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public string $text, public Point_Interface $position, public Font_Interface $font)
    {
    }
    /**
     * Decode text color
     *
     * The text outline effect is drawn with a trick by plotting additional text
     * under the actual text with an offset in the color of the outline effect.
     * For this reason, no colors with transparency can be used for the text
     * color or the color of the stroke effect, as this would be superimposed.
     *
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function text_color(): Color_Interface
    {
        $color = $this->driver()->handle_input($this->font->color());
        if ($this->font->has_stroke_effect() && $color->is_transparent()) {
            throw new Color_Exception('The text color must be fully opaque when using the stroke effect.');
        }
        return $color;
    }
    /**
     * Decode outline stroke color
     *
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function stroke_color(): Color_Interface
    {
        $color = $this->driver()->handle_input($this->font->stroke_color());
        if ($color->is_transparent()) {
            throw new Color_Exception('The stroke color must be fully opaque.');
        }
        return $color;
    }
    /**
     * Return array of offset points to draw text stroke effect below the actual text
     *
     * @return array<PointInterface>
     */
    protected function stroke_offsets(Font_Interface $font): array
    {
        $offsets = [];
        if ($font->stroke_width() <= 0) {
            return $offsets;
        }
        for ($x = $font->stroke_width() * -1; $x <= $font->stroke_width(); $x++) {
            for ($y = $font->stroke_width() * -1; $y <= $font->stroke_width(); $y++) {
                $offsets[] = new Point($x, $y);
            }
        }
        return $offsets;
    }
}