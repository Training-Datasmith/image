<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Interfaces\Drawable_Interface;
class Draw_Ellipse_Modifier extends Abstract_Draw_Modifier
{
    public function __construct(public Ellipse $drawable)
    {
    }
    public function drawable(): Drawable_Interface
    {
        return $this->drawable;
    }
}