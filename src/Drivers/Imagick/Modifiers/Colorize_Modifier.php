<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Colorize_Modifier as GenericColorizeModifier;
class Colorize_Modifier extends Generic_Colorize_Modifier implements Specialized_Interface
{
    public function apply(Image_Interface $image): Image_Interface
    {
        $red = $this->normalize_level($this->red);
        $green = $this->normalize_level($this->green);
        $blue = $this->normalize_level($this->blue);
        foreach ($image as $frame) {
            $qrange = $frame->native()->get_quantum_range();
            $frame->native()->level_image(0, $red, $qrange['quantumRangeLong'], Imagick::CHANNEL_RED);
            $frame->native()->level_image(0, $green, $qrange['quantumRangeLong'], Imagick::CHANNEL_GREEN);
            $frame->native()->level_image(0, $blue, $qrange['quantumRangeLong'], Imagick::CHANNEL_BLUE);
        }
        return $image;
    }
    private function normalize_level(int $level): int
    {
        return $level > 0 ? intval(round($level / 5)) : intval(round(($level + 100) / 100));
    }
}