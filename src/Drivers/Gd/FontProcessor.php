<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\Abstract_Font_Processor;
use Intervention\Image\Exceptions\Font_Exception;
use Intervention\Image\Geometry\Point;
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
        // if the font has no ttf file the box size is calculated
        // with gd's internal font system: integer values from 1-5
        if (!$font->has_filename()) {
            // calculate box size from gd font
            $box = new Rectangle(0, 0);
            $chars = mb_strlen($text);
            if ($chars > 0) {
                $box->set_width($chars * $this->gd_character_width((int) $font->filename()));
                $box->set_height($this->gd_character_height((int) $font->filename()));
            }
            return $box;
        }
        // build full path to font file to make sure to pass absolute path to imageftbbox()
        // because of issues with different GD version behaving differently when passing
        // relative paths to imageftbbox()
        $font_path = realpath($font->filename());
        if ($font_path === false) {
            throw new Font_Exception('Font file ' . $font->filename() . ' does not exist.');
        }
        // calculate box size from ttf font file with angle 0
        $box = imageftbbox(size: $this->native_font_size($font), angle: 0, font_filename: $font_path, string: $text);
        if ($box === false) {
            throw new Font_Exception('Unable to calculate box size of font ' . $font->filename() . '.');
        }
        // build size from points
        return new Rectangle(
            width: intval(abs($box[6] - $box[4])),
            // difference of upper-left-x and upper-right-x
            height: intval(abs($box[7] - $box[1])),
            // difference if upper-left-y and lower-left-y
            pivot: new Point($box[6], $box[7])
        );
    }
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::nativeFontSize()
     */
    public function native_font_size(Font_Interface $font): float
    {
        return floatval(round($font->size() * 0.76, 6));
    }
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::leading()
     */
    public function leading(Font_Interface $font): int
    {
        return (int) round(parent::leading($font) * 0.8);
    }
    /**
     * Return width of a single character
     */
    protected function gd_character_width(int $gdfont): int
    {
        return $gdfont + 4;
    }
    /**
     * Return height of a single character
     */
    protected function gd_character_height(int $gdfont): int
    {
        return match ($gdfont) {
            2, 3 => 14,
            4, 5 => 16,
            default => 8,
        };
    }
}