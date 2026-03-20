<?php

declare (strict_types=1);
namespace Intervention\Image;

use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Exceptions\Driver_Exception;
use Intervention\Image\Exceptions\Input_Exception;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Driver_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Image_Manager_Interface;
final class Image_Manager implements Image_Manager_Interface
{
    private readonly Driver_Interface $driver;
    /**
     * @link https://image.intervention.io/v3/basics/configuration-drivers#create-a-new-image-manager-instance
     *
     * @throws DriverException
     * @throws InputException
     */
    public function __construct(string|Driver_Interface $driver, mixed ...$options)
    {
        $this->driver = self::resolve_driver($driver, ...$options);
    }
    /**
     * Create image manager with given driver
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#static-constructor
     *
     * @throws DriverException
     * @throws InputException
     */
    public static function with_driver(string|Driver_Interface $driver, mixed ...$options): self
    {
        return new self(self::resolve_driver($driver, ...$options));
    }
    /**
     * Create image manager with GD driver
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#static-gd-driver-constructor
     *
     * @throws DriverException
     * @throws InputException
     */
    public static function gd(mixed ...$options): self
    {
        return self::with_driver(new Gd_Driver(), ...$options);
    }
    /**
     * Create image manager with Imagick driver
     *
     * @link https://image.intervention.io/v3/basics/configuration-drivers#static-imagick-driver-constructor
     *
     * @throws DriverException
     * @throws InputException
     */
    public static function imagick(mixed ...$options): self
    {
        return self::with_driver(new Imagick_Driver(), ...$options);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::create()
     */
    public function create(int $width, int $height): Image_Interface
    {
        return $this->driver->create_image($width, $height);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::read()
     */
    public function read(mixed $input, string|array|Decoder_Interface $decoders = []): Image_Interface
    {
        return $this->driver->handle_input($input, match (true) {
            is_string($decoders), is_a($decoders, Decoder_Interface::class) => [$decoders],
            default => $decoders,
        });
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::animate()
     */
    public function animate(callable $init): Image_Interface
    {
        return $this->driver->create_animation($init);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageManagerInterface::driver()
     */
    public function driver(): Driver_Interface
    {
        return $this->driver;
    }
    /**
     * Return driver object from given input which might be driver classname or instance of DriverInterface
     *
     * @throws DriverException
     * @throws InputException
     */
    private static function resolve_driver(string|Driver_Interface $driver, mixed ...$options): Driver_Interface
    {
        $driver = match (true) {
            $driver instanceof Driver_Interface => $driver,
            class_exists($driver) => new $driver(),
            default => throw new Driver_Exception('Unable to resolve driver. Argment must be either an instance of ' . Driver_Interface::class . '::class or a qualified namespaced name of the driver class.'),
        };
        if (!$driver instanceof Driver_Interface) {
            throw new Driver_Exception('Unable to resolve driver. Driver object must implement ' . Driver_Interface::class . '.');
        }
        $driver->config()->set_options(...$options);
        return $driver;
    }
}