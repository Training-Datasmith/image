<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Interfaces\Colorspace_Interface;
class Colorspace_Modifier extends Specializable_Modifier
{
    public function __construct(public string|Colorspace_Interface $target)
    {
    }
    /**
     * @throws NotSupportedException
     */
    public function target_colorspace(): Colorspace_Interface
    {
        if (is_object($this->target)) {
            return $this->target;
        }
        if (in_array($this->target, ['rgb', 'RGB', Rgb_Colorspace::class])) {
            return new Rgb_Colorspace();
        }
        if (in_array($this->target, ['cmyk', 'CMYK', Cmyk_Colorspace::class])) {
            return new Cmyk_Colorspace();
        }
        throw new Not_Supported_Exception('Given colorspace is not supported.');
    }
}