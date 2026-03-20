<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Contain_Modifier extends Specializable_Modifier
{
    public function __construct(public int $width, public int $height, public mixed $background = 'ffffff', public string $position = 'center')
    {
    }
    /**
     * @throws RuntimeException
     */
    public function get_crop_size(Image_Interface $image): Size_Interface
    {
        return $image->size()->contain($this->width, $this->height)->align_pivot_to($this->get_resize_size($image), $this->position);
    }
    public function get_resize_size(Image_Interface $image): Size_Interface
    {
        return new Rectangle($this->width, $this->height);
    }
}