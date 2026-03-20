<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Point_Interface;
class Place_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public mixed $element, public string $position = 'top-left', public int $offset_x = 0, public int $offset_y = 0, public int $opacity = 100)
    {
    }
    /**
     * @throws RuntimeException
     */
    public function get_position(Image_Interface $image, Image_Interface $watermark): Point_Interface
    {
        $image_size = $image->size()->move_pivot($this->position, $this->offset_x, $this->offset_y);
        $watermark_size = $watermark->size()->move_pivot($this->position);
        return $image_size->relative_position_to($watermark_size);
    }
}