<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

interface Profile_Interface
{
    /**
     * Cast color profile object to string
     */
    public function __toString(): string;
}