<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Cmyk;

use Intervention\Image\Colors\Abstract_Color;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Input_Handler;
use Intervention\Image\Interfaces\Color_Channel_Interface;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
class Color extends Abstract_Color
{
    /**
     * Create new instance
     */
    public function __construct(int $c, int $m, int $y, int $k)
    {
        /** @throws void */
        $this->channels = [new Cyan($c), new Magenta($m), new Yellow($y), new Key($k)];
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
     * @see ColorInterface::toHex()
     */
    public function to_hex(string $prefix = ''): string
    {
        return $this->convert_to(Rgb_Colorspace::class)->to_hex($prefix);
    }
    /**
     * Return the CMYK cyan channel
     */
    public function cyan(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Cyan::class);
    }
    /**
     * Return the CMYK magenta channel
     */
    public function magenta(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Magenta::class);
    }
    /**
     * Return the CMYK yellow channel
     */
    public function yellow(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Yellow::class);
    }
    /**
     * Return the CMYK key channel
     */
    public function key(): Color_Channel_Interface
    {
        /** @throws void */
        return $this->channel(Key::class);
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function to_string(): string
    {
        return sprintf('cmyk(%d%%, %d%%, %d%%, %d%%)', $this->cyan()->value(), $this->magenta()->value(), $this->yellow()->value(), $this->key()->value());
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGreyscale()
     */
    public function is_greyscale(): bool
    {
        return 0 === array_sum([$this->cyan()->value(), $this->magenta()->value(), $this->yellow()->value()]);
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