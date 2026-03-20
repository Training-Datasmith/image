<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd;

use Gd_Image;
use Intervention\Image\Drivers\Abstract_Frame;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Exceptions\Input_Exception;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\Driver_Interface;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Frame extends Abstract_Frame implements Frame_Interface
{
    /**
     * Create new frame instance
     */
    public function __construct(protected Gd_Image $native, protected float $delay = 0, protected int $dispose = 1, protected int $offset_left = 0, protected int $offset_top = 0)
    {
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::toImage()
     */
    public function to_image(Driver_Interface $driver): Image_Interface
    {
        return new Image($driver, new Core([$this]));
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setNative()
     */
    public function set_native(mixed $native): Frame_Interface
    {
        $this->native = $native;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::native()
     */
    public function native(): Gd_Image
    {
        return $this->native;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::size()
     */
    public function size(): Size_Interface
    {
        return new Rectangle(imagesx($this->native), imagesy($this->native));
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::delay()
     */
    public function delay(): float
    {
        return $this->delay;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setDelay()
     */
    public function set_delay(float $delay): Frame_Interface
    {
        $this->delay = $delay;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::dispose()
     */
    public function dispose(): int
    {
        return $this->dispose;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setDispose()
     *
     * @throws InputException
     */
    public function set_dispose(int $dispose): Frame_Interface
    {
        if (!in_array($dispose, [0, 1, 2, 3])) {
            throw new Input_Exception('Value for argument $dispose must be 0, 1, 2 or 3.');
        }
        $this->dispose = $dispose;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setOffset()
     */
    public function set_offset(int $left, int $top): Frame_Interface
    {
        $this->offset_left = $left;
        $this->offset_top = $top;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::offsetLeft()
     */
    public function offset_left(): int
    {
        return $this->offset_left;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setOffsetLeft()
     */
    public function set_offset_left(int $offset): Frame_Interface
    {
        $this->offset_left = $offset;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::offsetTop()
     */
    public function offset_top(): int
    {
        return $this->offset_top;
    }
    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setOffsetTop()
     */
    public function set_offset_top(int $offset): Frame_Interface
    {
        $this->offset_top = $offset;
        return $this;
    }
    /**
     * This workaround helps cloning GdImages which is currently not possible.
     *
     * @throws ColorException
     */
    public function __clone(): void
    {
        $this->native = Cloner::clone($this->native);
    }
}