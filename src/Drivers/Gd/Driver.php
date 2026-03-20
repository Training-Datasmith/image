<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\Abstract_Driver;
use Intervention\Image\Exceptions\Driver_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\File_Extension;
use Intervention\Image\Format;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\Color_Processor_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
use Intervention\Image\Interfaces\Driver_Interface;
use Intervention\Image\Interfaces\Font_Processor_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Media_Type;
class Driver extends Abstract_Driver
{
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::id()
     */
    public function id(): string
    {
        return 'GD';
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::checkHealth()
     *
     * @codeCoverageIgnore
     */
    public function check_health(): void
    {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            throw new Driver_Exception('GD PHP extension must be installed to use this driver.');
        }
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createImage()
     */
    public function create_image(int $width, int $height): Image_Interface
    {
        // build new transparent GDImage
        $data = imagecreatetruecolor($width, $height);
        imagesavealpha($data, true);
        $background = imagecolorallocatealpha($data, 255, 255, 255, 127);
        imagealphablending($data, false);
        imagefill($data, 0, 0, $background);
        imagecolortransparent($data, $background);
        return new Image($this, new Core([new Frame($data)]));
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createAnimation()
     *
     * @throws RuntimeException
     */
    public function create_animation(callable $init): Image_Interface
    {
        $animation = new class($this)
        {
            public function __construct(protected Driver_Interface $driver, public Core $core = new Core())
            {
            }
            /**
             * @throws RuntimeException
             */
            public function add(mixed $source, float $delay = 1): self
            {
                $this->core->add($this->driver->handle_input($source)->core()->first()->set_delay($delay));
                return $this;
            }
            /**
             * @throws RuntimeException
             */
            public function __invoke(): Image_Interface
            {
                return new Image($this->driver, $this->core);
            }
        };
        $init($animation);
        return call_user_func($animation);
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::colorProcessor()
     */
    public function color_processor(Colorspace_Interface $colorspace): Color_Processor_Interface
    {
        return new Color_Processor($colorspace);
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::fontProcessor()
     */
    public function font_processor(): Font_Processor_Interface
    {
        return new Font_Processor();
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::supports()
     */
    public function supports(string|Format|File_Extension|Media_Type $identifier): bool
    {
        return match (Format::try_create($identifier)) {
            Format::JPEG => boolval(imagetypes() & IMG_JPEG),
            Format::WEBP => boolval(imagetypes() & IMG_WEBP),
            Format::GIF => boolval(imagetypes() & IMG_GIF),
            Format::PNG => boolval(imagetypes() & IMG_PNG),
            Format::AVIF => boolval(imagetypes() & IMG_AVIF),
            Format::BMP => boolval(imagetypes() & IMG_BMP),
            default => false,
        };
    }
    /**
     * Return version of GD library
     */
    public static function version(): string
    {
        return gd_info()['GD Version'];
    }
}