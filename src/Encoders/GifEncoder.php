<?php

declare (strict_types=1);
namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\Specializable_Encoder;
class Gif_Encoder extends Specializable_Encoder
{
    /**
     * Create new encoder object
     */
    public function __construct(public bool $interlaced = false)
    {
    }
}