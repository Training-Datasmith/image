<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use Imagick_Exception;
use Imagick_Pixel;
use Intervention\Image\Drivers\Abstract_Frame;
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
     * Create new frame object
     *
     * @throws ImagickException
     */
    public function __construct(protected Imagick $native)
    {
        $background = new Imagick_Pixel('rgba(255, 255, 255, 0)');
        $this->native->set_image_background_color($background);
        $this->native->set_background_color($background);
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::toImage()
     */
    public function to_image(Driver_Interface $driver): Image_Interface
    {
        return new Image($driver, new Core($this->native()));
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setNative()
     */
    public function set_native(mixed $native): Frame_Interface
    {
        $this->native = $native;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::native()
     */
    public function native(): Imagick
    {
        return $this->native;
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::size()
     */
    public function size(): Size_Interface
    {
        return new Rectangle($this->native->get_image_width(), $this->native->get_image_height());
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::delay()
     */
    public function delay(): float
    {
        return $this->native->get_image_delay() / 100;
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setDelay()
     */
    public function set_delay(float $delay): Frame_Interface
    {
        $this->native->set_image_delay(intval(round($delay * 100)));
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::dispose()
     */
    public function dispose(): int
    {
        return $this->native->get_image_dispose();
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setDispose()
     *
     * @throws InputException
     */
    public function set_dispose(int $dispose): Frame_Interface
    {
        if (!in_array($dispose, [0, 1, 2, 3])) {
            throw new Input_Exception('Value for argument $dispose must be 0, 1, 2 or 3.');
        }
        $this->native->set_image_dispose($dispose);
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setOffset()
     */
    public function set_offset(int $left, int $top): Frame_Interface
    {
        $this->native->set_image_page($this->native->get_image_width(), $this->native->get_image_height(), $left, $top);
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::offsetLeft()
     */
    public function offset_left(): int
    {
        return $this->native->get_image_page()['x'];
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setOffsetLeft()
     */
    public function set_offset_left(int $offset): Frame_Interface
    {
        return $this->set_offset($offset, $this->offset_top());
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::offsetTop()
     */
    public function offset_top(): int
    {
        return $this->native->get_image_page()['y'];
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setOffsetTop()
     */
    public function set_offset_top(int $offset): Frame_Interface
    {
        return $this->set_offset($this->offset_left(), $offset);
    }
}