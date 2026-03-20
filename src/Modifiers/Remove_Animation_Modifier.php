<?php

declare (strict_types=1);
namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\Specializable_Modifier;
use Intervention\Image\Exceptions\Input_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Remove_Animation_Modifier extends Specializable_Modifier
{
    public function __construct(public int|string $position = 0)
    {
    }
    /**
     * @throws RuntimeException
     */
    protected function selected_frame(Image_Interface $image): Frame_Interface
    {
        return $image->core()->frame($this->normalize_position($image));
    }
    /**
     * Return the position of the selected frame as integer
     *
     * @throws InputException
     */
    protected function normalize_position(Image_Interface $image): int
    {
        if (is_int($this->position)) {
            return $this->position;
        }
        if (is_numeric($this->position)) {
            return (int) $this->position;
        }
        // calculate position from percentage value
        if (preg_match('/^(?P<percent>[0-9]{1,3})%$/', $this->position, $matches) != 1) {
            throw new Input_Exception('Position must be either integer or a percent value as string.');
        }
        $total = count($image);
        $position = intval(round($total / 100 * intval($matches['percent'])));
        return $position == $total ? $position - 1 : $position;
    }
}