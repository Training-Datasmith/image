<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Line;
use Intervention\Image\Interfaces\Drawable_Interface;
class Draw_Line_Modifier extends Abstract_Draw_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public Line $drawable)
    {
    }
    /**
     * Return object to be drawn
     */
    public function drawable(): Drawable_Interface
    {
        return $this->drawable;
    }
}