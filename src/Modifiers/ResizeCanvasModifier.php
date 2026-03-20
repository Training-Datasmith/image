<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Resize_Canvas_Modifier extends Specializable_Modifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public ?int $width = null, public ?int $height = null, public mixed $background = 'ffffff', public string $position = 'center')
    {
    }
    /**
     * Build the crop size to be used for the ResizeCanvas process
     *
     * @throws RuntimeException
     */
    protected function crop_size(Image_Interface $image, bool $relative = false): Size_Interface
    {
        $size = match ($relative) {
            true => new Rectangle(is_null($this->width) ? $image->width() : $image->width() + $this->width, is_null($this->height) ? $image->height() : $image->height() + $this->height),
            default => new Rectangle(is_null($this->width) ? $image->width() : $this->width, is_null($this->height) ? $image->height() : $this->height),
        };
        return $size->align_pivot_to($image->size(), $this->position);
    }
}