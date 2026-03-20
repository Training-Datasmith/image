<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Interfaces\Colorspace_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Colorspace_Modifier as GenericColorspaceModifier;
class Colorspace_Modifier extends Generic_Colorspace_Modifier implements Specialized_Interface
{
    /**
     * Map own colorspace classname to Imagick classnames
     *
     * @var array<string, int>
     */
    protected static array $mapping = [Rgb_Colorspace::class => Imagick::COLORSPACE_SRGB, Cmyk_Colorspace::class => Imagick::COLORSPACE_CMYK];
    public function apply(Image_Interface $image): Image_Interface
    {
        $colorspace = $this->target_colorspace();
        $imagick = $image->core()->native();
        $imagick->transform_image_colorspace($this->get_imagick_colorspace($colorspace));
        return $image;
    }
    /**
     * @throws NotSupportedException
     */
    private function get_imagick_colorspace(Colorspace_Interface $colorspace): int
    {
        if (!array_key_exists($colorspace::class, self::$mapping)) {
            throw new Not_Supported_Exception('Given colorspace is not supported.');
        }
        return self::$mapping[$colorspace::class];
    }
}