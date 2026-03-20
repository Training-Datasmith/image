<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\Frame_Interface;
abstract class Abstract_Frame implements Frame_Interface
{
    /**
     * Show debug info for the current image
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return ['delay' => $this->delay(), 'left' => $this->offset_left(), 'top' => $this->offset_top(), 'dispose' => $this->dispose()];
    }
}