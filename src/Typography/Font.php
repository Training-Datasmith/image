<?php

declare (strict_types=1);
namespace Intervention\Image\Typography;

use Intervention\Image\Exceptions\Font_Exception;
use Intervention\Image\Interfaces\Font_Interface;
class Font implements Font_Interface
{
    protected float $size = 12;
    protected float $angle = 0;
    protected mixed $color = '000000';
    protected mixed $stroke_color = 'ffffff';
    protected int $stroke_width = 0;
    protected string $alignment = 'left';
    protected string $valignment = 'bottom';
    protected float $line_height = 1.25;
    protected ?int $wrap_width = null;
    public function __construct(protected ?string $filename = null)
    {
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setSize()
     */
    public function set_size(float $size): Font_Interface
    {
        $this->size = $size;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::size()
     */
    public function size(): float
    {
        return $this->size;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setAngle()
     */
    public function set_angle(float $angle): Font_Interface
    {
        $this->angle = $angle;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::angle()
     */
    public function angle(): float
    {
        return $this->angle;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setFilename()
     *
     * @throws FontException
     */
    public function set_filename(string $filename): Font_Interface
    {
        if (!file_exists($filename)) {
            throw new Font_Exception('Font file ' . $filename . ' does not exist.');
        }
        $this->filename = $filename;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::filename()
     */
    public function filename(): ?string
    {
        return $this->filename;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::hasFilename()
     */
    public function has_filename(): bool
    {
        return !is_null($this->filename) && is_file($this->filename);
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setColor()
     */
    public function set_color(mixed $color): Font_Interface
    {
        $this->color = $color;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::color()
     */
    public function color(): mixed
    {
        return $this->color;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setStrokeColor()
     */
    public function set_stroke_color(mixed $color): Font_Interface
    {
        $this->stroke_color = $color;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::strokeColor()
     */
    public function stroke_color(): mixed
    {
        return $this->stroke_color;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setStrokeWidth()
     */
    public function set_stroke_width(int $width): Font_Interface
    {
        if (!in_array($width, range(0, 10))) {
            throw new Font_Exception('The stroke width must be in the range from 0 to 10.');
        }
        $this->stroke_width = $width;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::strokeWidth()
     */
    public function stroke_width(): int
    {
        return $this->stroke_width;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::hasStrokeEffect()
     */
    public function has_stroke_effect(): bool
    {
        return $this->stroke_width > 0;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::alignment()
     */
    public function alignment(): string
    {
        return $this->alignment;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setAlignment()
     */
    public function set_alignment(string $value): Font_Interface
    {
        $this->alignment = $value;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::valignment()
     */
    public function valignment(): string
    {
        return $this->valignment;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setValignment()
     */
    public function set_valignment(string $value): Font_Interface
    {
        $this->valignment = $value;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setLineHeight()
     */
    public function set_line_height(float $height): Font_Interface
    {
        $this->line_height = $height;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::lineHeight()
     */
    public function line_height(): float
    {
        return $this->line_height;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setWrapWidth()
     */
    public function set_wrap_width(?int $width): Font_Interface
    {
        $this->wrap_width = $width;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FontInterface::wrapWidth()
     */
    public function wrap_width(): ?int
    {
        return $this->wrap_width;
    }
}