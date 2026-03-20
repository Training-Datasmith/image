<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use Imagick_Pixel;
use Intervention\Image\Drivers\Abstract_Driver;
use Intervention\Image\Exceptions\Driver_Exception;
use Intervention\Image\Exceptions\Not_Supported_Exception;
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
        return 'Imagick';
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
        if (!extension_loaded('imagick') || !class_exists('Imagick')) {
            throw new Driver_Exception('Imagick PHP extension must be installed to use this driver.');
        }
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createImage()
     */
    public function create_image(int $width, int $height): Image_Interface
    {
        $background = new Imagick_Pixel('rgba(255, 255, 255, 0)');
        $imagick = new Imagick();
        $imagick->new_image($width, $height, $background, 'png');
        $imagick->set_type(Imagick::IMGTYPE_UNDEFINED);
        $imagick->set_image_type(Imagick::IMGTYPE_UNDEFINED);
        $imagick->set_colorspace(Imagick::COLORSPACE_SRGB);
        $imagick->set_image_resolution(96, 96);
        $imagick->set_image_background_color($background);
        return new Image($this, new Core($imagick));
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
        $imagick = new Imagick();
        $imagick->set_format('gif');
        $animation = new class($this, $imagick)
        {
            public function __construct(protected Driver_Interface $driver, public Imagick $imagick)
            {
            }
            /**
             * @throws RuntimeException
             */
            public function add(mixed $source, float $delay = 1): self
            {
                $native = $this->driver->handle_input($source)->core()->native();
                $native->set_image_delay(intval(round($delay * 100)));
                $this->imagick->add_image($native);
                return $this;
            }
            /**
             * @throws RuntimeException
             */
            public function __invoke(): Image_Interface
            {
                return new Image($this->driver, new Core($this->imagick));
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
        try {
            $format = Format::create($identifier);
        } catch (Not_Supported_Exception) {
            return false;
        }
        return count(Imagick::query_formats($format->name)) >= 1;
    }
    /**
     * Return version of ImageMagick library
     *
     * @throws DriverException
     */
    public static function version(): string
    {
        $pattern = '/^ImageMagick (?P<version>(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)' . '(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?' . '(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?)/';
        if (preg_match($pattern, Imagick::get_version()['versionString'], $matches) !== 1) {
            throw new Driver_Exception('Unable to read ImageMagick version number.');
        }
        return $matches['version'];
    }
}