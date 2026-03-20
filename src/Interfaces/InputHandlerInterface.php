<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;
interface Input_Handler_Interface
{
    /**
     * Try to decode the given input with each decoder of the the handler chain
     *
     * @throws RuntimeException
     */
    public function handle(mixed $input): Image_Interface|Color_Interface;
}