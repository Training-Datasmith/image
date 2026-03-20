<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;
interface File_Interface
{
    /**
     * Save data in given path in file system
     *
     * @throws RuntimeException
     */
    public function save(string $filepath): void;
    /**
     * Create file pointer from encoded data
     *
     * @return resource
     */
    public function to_file_pointer();
    /**
     * Return size in bytes
     */
    public function size(): int;
    /**
     * Turn encoded data into string
     */
    public function to_string(): string;
    /**
     * Cast encoded data into string
     */
    public function __toString(): string;
}