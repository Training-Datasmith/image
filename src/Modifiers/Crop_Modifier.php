<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Crop_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public int $width, public int $height, public int $offset_x = 0, public int $offset_y = 0, public mixed $background = 'ffffff', public string $position = 'top-left')
    {
    }
    /**
     * @throws RuntimeException
     */
    public function crop(Image_Interface $image): Size_Interface
    {
        $crop = new Rectangle($this->width, $this->height);
        $crop->align($this->position);
        return $crop->align_pivot_to($image->size(), $this->position);
    }
}