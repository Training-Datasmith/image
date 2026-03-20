<?php

declare (strict_types=1);
namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\File;
use Intervention\Image\Interfaces\Profile_Interface;
class Profile extends File implements Profile_Interface
{
    /**
     * Create profile object from path in file system
     *
     * @throws RuntimeException
     */
    public static function from_path(string $path): self
    {
        return new self(fopen($path, 'r'));
    }
}