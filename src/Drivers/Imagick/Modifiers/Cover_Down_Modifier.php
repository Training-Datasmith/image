<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\Geometry_Exception;
use Intervention\Image\Interfaces\Size_Interface;
class Cover_Down_Modifier extends Cover_Modifier
{
    /**
     * @throws GeometryException
     */
    public function get_resize_size(Size_Interface $size): Size_Interface
    {
        return $size->resize_down($this->width, $this->height);
    }
}