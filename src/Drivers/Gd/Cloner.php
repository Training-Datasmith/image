<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd;

use Gd_Image;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Size_Interface;
class Cloner
{
    /**
     * Create a clone of the given GdImage
     *
     * @throws ColorException
     */
    public static function clone(Gd_Image $gd): Gd_Image
    {
        // create empty canvas with same size
        $clone = static::clone_empty($gd);
        // transfer actual image to clone
        imagecopy($clone, $gd, 0, 0, 0, 0, imagesx($gd), imagesy($gd));
        return $clone;
    }
    /**
     * Create an "empty" clone of the given GdImage
     *
     * This only retains the basic data without transferring the actual image.
     * It is optionally possible to change the size of the result and set a
     * background color.
     *
     * @throws ColorException
     */
    public static function clone_empty(Gd_Image $gd, ?Size_Interface $size = null, Color_Interface $background = new Color(255, 255, 255, 0)): Gd_Image
    {
        // define size
        $size = $size ?: new Rectangle(imagesx($gd), imagesy($gd));
        // create new gd image with same size or new given size
        $clone = imagecreatetruecolor($size->width(), $size->height());
        // copy resolution to clone
        $resolution = imageresolution($gd);
        if (is_array($resolution) && array_key_exists(0, $resolution) && array_key_exists(1, $resolution)) {
            imageresolution($clone, $resolution[0], $resolution[1]);
        }
        // fill with background
        $processor = new Color_Processor();
        imagefill($clone, 0, 0, $processor->color_to_native($background));
        imagealphablending($clone, true);
        imagesavealpha($clone, true);
        // set background image as transparent if alpha channel value if color is below .5
        // comes into effect when the end format only supports binary transparency (like GIF)
        if ($background->channel(Alpha::class)->value() < 128) {
            imagecolortransparent($clone, $processor->color_to_native($background));
        }
        return $clone;
    }
    /**
     * Create a clone of an GdImage that is positioned on the specified background color.
     * Possible transparent areas are mixed with this color.
     *
     * @throws ColorException
     */
    public static function clone_blended(Gd_Image $gd, Color_Interface $background): Gd_Image
    {
        // create empty canvas with same size
        $clone = static::clone_empty($gd, background: $background);
        // transfer actual image to clone
        imagecopy($clone, $gd, 0, 0, 0, 0, imagesx($gd), imagesy($gd));
        return $clone;
    }
}