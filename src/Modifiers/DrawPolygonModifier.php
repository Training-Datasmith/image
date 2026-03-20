<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Interfaces\Drawable_Interface;
class Draw_Polygon_Modifier extends Abstract_Draw_Modifier
{
    public function __construct(public Polygon $drawable)
    {
    }
    public function drawable(): Drawable_Interface
    {
        return $this->drawable;
    }
}