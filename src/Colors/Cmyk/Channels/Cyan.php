<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Cmyk\Channels;

use Intervention\Image\Colors\Abstract_Color_Channel;
class Cyan extends Abstract_Color_Channel
{
    public function min(): int
    {
        return 0;
    }
    public function max(): int
    {
        return 100;
    }
}