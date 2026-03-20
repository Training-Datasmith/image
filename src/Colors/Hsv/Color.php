<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Hsv;

use Intervention\Image\Colors\Abstract_Color;
use Intervention\Image\Colors\Hsv\Channels\Hue;
use Intervention\Image\Colors\Hsv\Channels\Saturation;
use Intervention\Image\Colors\Hsv\Channels\Value;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Input_Handler;
use Intervention\Image\Interfaces\Color_Channel_Interface;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
class Color extends Abstract_Color
{
    /**
     * Create new color object
     */
    public function __construct(int $h, int $s, int $v)
    {
        /** @throws void */
        $this->channels = [new Hue($h), new Saturation($s), new Value($v)];
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::colorspace()
     */
    public function colorspace(): Colorspace_Interface
    {
        return new Colorspace();
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::create()
     */
    public static function create(mixed $input): Color_Interface
    {
        return Input_Handler::with_decoders([Decoders\String_Color_Decoder::class])->handle($input);
    }
    /**
     * Return the Hue channel
     */
    public function hue(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Hue::class);
    }
    /**
     * Return the Saturation channel
     */
    public function saturation(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Saturation::class);
    }
    /**
     * Return the Value channel
     */
    public function value(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Value::class);
    }
    public function to_hex(string $prefix = ''): string
    {
        return $this->convert_to(Rgb_Colorspace::class)->to_hex($prefix);
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function to_string(): string
    {
        return sprintf('hsv(%d, %d%%, %d%%)', $this->hue()->value(), $this->saturation()->value(), $this->value()->value());
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGreyscale()
     */
    public function is_greyscale(): bool
    {
        return $this->saturation()->value() == 0;
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isTransparent()
     */
    public function is_transparent(): bool
    {
        return false;
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isClear()
     */
    public function is_clear(): bool
    {
        return false;
    }
}