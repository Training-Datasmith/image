<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Cover_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public int $width, public int $height, public string $position = 'center')
    {
    }
    /**
     * @throws RuntimeException
     */
    public function get_crop_size(Image_Interface $image): Size_Interface
    {
        $imagesize = $image->size();
        $crop = new Rectangle($this->width, $this->height);
        return $crop->contain($imagesize->width(), $imagesize->height())->align_pivot_to($imagesize, $this->position);
    }
    /**
     * @throws RuntimeException
     */
    public function get_resize_size(Size_Interface $size): Size_Interface
    {
        return $size->resize($this->width, $this->height);
    }
}