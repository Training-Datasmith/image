<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\Animation_Exception;
use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Trim_Modifier as GenericTrimModifier;
class Trim_Modifier extends Generic_Trim_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        if ($image->is_animated()) {
            throw new Not_Supported_Exception('Trim modifier cannot be applied to animated images.');
        }
        // apply tolerance with a min. value of .5 because the default tolerance of '0' should
        // already trim away similar colors which is not the case with imagecropauto.
        $trimmed = imagecropauto($image->core()->native(), IMG_CROP_THRESHOLD, max([0.5, $this->tolerance / 10]), $this->trim_color($image));
        // if the tolerance is very high, it is possible that no image is left.
        // imagick returns a 1x1 pixel image in this case. this does the same.
        if ($trimmed === false) {
            $trimmed = $this->driver()->create_image(1, 1)->core()->native();
        }
        $image->core()->set_native($trimmed);
        return $image;
    }
    /**
     * Create an average color from the colors of the four corner points of the given image
     *
     * @throws RuntimeException
     * @throws AnimationException
     */
    private function trim_color(Image_Interface $image): int
    {
        // trim color base
        $red = 0;
        $green = 0;
        $blue = 0;
        // corner coordinates
        $size = $image->size();
        $corner_points = [new Point(0, 0), new Point($size->width() - 1, 0), new Point(0, $size->height() - 1), new Point($size->width() - 1, $size->height() - 1)];
        // create an average color to be used in trim operation
        foreach ($corner_points as $pos) {
            $corner_color = imagecolorat($image->core()->native(), $pos->x(), $pos->y());
            $rgb = imagecolorsforindex($image->core()->native(), $corner_color);
            $red += round(round($rgb['red'] / 51) * 51);
            $green += round(round($rgb['green'] / 51) * 51);
            $blue += round(round($rgb['blue'] / 51) * 51);
        }
        $red = (int) round($red / 4);
        $green = (int) round($green / 4);
        $blue = (int) round($blue / 4);
        return imagecolorallocate($image->core()->native(), $red, $green, $blue);
    }
}