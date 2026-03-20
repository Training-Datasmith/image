<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Colors\Abstract_Color;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Input_Handler;
use Intervention\Image\Interfaces\Color_Channel_Interface;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
class Color extends Abstract_Color
{
    /**
     * Create new instance
     *
     * @return ColorInterface
     */
    public function __construct(int $r, int $g, int $b, int $a = 255)
    {
        /** @throws void */
        $this->channels = [new Red($r), new Green($g), new Blue($b), new Alpha($a)];
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
        return Input_Handler::with_decoders([Decoders\Hex_Color_Decoder::class, Decoders\String_Color_Decoder::class, Decoders\Transparent_Color_Decoder::class, Decoders\Html_Colorname_Decoder::class])->handle($input);
    }
    /**
     * Return the RGB red color channel
     */
    public function red(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Red::class);
    }
    /**
     * Return the RGB green color channel
     */
    public function green(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Green::class);
    }
    /**
     * Return the RGB blue color channel
     */
    public function blue(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Blue::class);
    }
    /**
     * Return the colors alpha channel
     */
    public function alpha(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Alpha::class);
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toHex()
     */
    public function to_hex(string $prefix = ''): string
    {
        if ($this->is_transparent()) {
            return sprintf('%s%02x%02x%02x%02x', $prefix, $this->red()->value(), $this->green()->value(), $this->blue()->value(), $this->alpha()->value());
        }
        return sprintf('%s%02x%02x%02x', $prefix, $this->red()->value(), $this->green()->value(), $this->blue()->value());
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function to_string(): string
    {
        if ($this->is_transparent()) {
            return sprintf('rgba(%d, %d, %d, %.1F)', $this->red()->value(), $this->green()->value(), $this->blue()->value(), $this->alpha()->normalize());
        }
        return sprintf('rgb(%d, %d, %d)', $this->red()->value(), $this->green()->value(), $this->blue()->value());
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGreyscale()
     */
    public function is_greyscale(): bool
    {
        $values = [$this->red()->value(), $this->green()->value(), $this->blue()->value()];
        return count(array_unique($values, SORT_REGULAR)) === 1;
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isTransparent()
     */
    public function is_transparent(): bool
    {
        return $this->alpha()->value() < $this->alpha()->max();
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isClear()
     */
    public function is_clear(): bool
    {
        return $this->alpha()->value() == 0;
    }
}