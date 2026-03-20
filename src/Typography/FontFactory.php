<?php

declare (strict_types=1);
namespace Intervention\Image\Typography;

use Closure;
use Intervention\Image\Exceptions\Font_Exception;
use Intervention\Image\Interfaces\Font_Interface;
class Font_Factory
{
    protected Font_Interface $font;
    /**
     * Create new instance
     *
     * @param Closure|FontInterface $init
     * @throws FontException
     */
    public function __construct(callable|Closure|Font_Interface $init)
    {
        $this->font = is_a($init, Font_Interface::class) ? $init : new Font();
        if (is_callable($init)) {
            $init($this);
        }
    }
    /**
     * Set the filename of the font to be built
     */
    public function filename(string $value): self
    {
        $this->font->set_filename($value);
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see self::filename()
     */
    public function file(string $value): self
    {
        return $this->filename($value);
    }
    /**
     * Set outline stroke effect for the font to be built
     *
     * @throws FontException
     */
    public function stroke(mixed $color, int $width = 1): self
    {
        $this->font->set_stroke_width($width);
        $this->font->set_stroke_color($color);
        return $this;
    }
    /**
     * Set color for the font to be built
     */
    public function color(mixed $value): self
    {
        $this->font->set_color($value);
        return $this;
    }
    /**
     * Set the size for the font to be built
     */
    public function size(float $value): self
    {
        $this->font->set_size($value);
        return $this;
    }
    /**
     * Set the horizontal alignment of the font to be built
     */
    public function align(string $value): self
    {
        $this->font->set_alignment($value);
        return $this;
    }
    /**
     * Set the vertical alignment of the font to be built
     */
    public function valign(string $value): self
    {
        $this->font->set_valignment($value);
        return $this;
    }
    /**
     * Set the line height of the font to be built
     */
    public function line_height(float $value): self
    {
        $this->font->set_line_height($value);
        return $this;
    }
    /**
     * Set the rotation angle of the font to be built
     */
    public function angle(float $value): self
    {
        $this->font->set_angle($value);
        return $this;
    }
    /**
     * Set the maximum width of the text block to be built
     */
    public function wrap(int $width): self
    {
        $this->font->set_wrap_width($width);
        return $this;
    }
    /**
     * Build font
     */
    public function __invoke(): Font_Interface
    {
        return $this->font;
    }
}