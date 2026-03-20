<?php

declare (strict_types=1);
namespace Intervention\Image;

use Closure;
use Intervention\Image\Analyzers\Colorspace_Analyzer;
use Intervention\Image\Analyzers\Height_Analyzer;
use Intervention\Image\Analyzers\Pixel_Color_Analyzer;
use Intervention\Image\Analyzers\Pixel_Colors_Analyzer;
use Intervention\Image\Analyzers\Profile_Analyzer;
use Intervention\Image\Analyzers\Resolution_Analyzer;
use Intervention\Image\Analyzers\Width_Analyzer;
use Intervention\Image\Encoders\Auto_Encoder;
use Intervention\Image\Encoders\Avif_Encoder;
use Intervention\Image\Encoders\Bmp_Encoder;
use Intervention\Image\Encoders\File_Extension_Encoder;
use Intervention\Image\Encoders\File_Path_Encoder;
use Intervention\Image\Encoders\Gif_Encoder;
use Intervention\Image\Encoders\Heic_Encoder;
use Intervention\Image\Encoders\Jpeg2000Encoder;
use Intervention\Image\Encoders\Jpeg_Encoder;
use Intervention\Image\Encoders\Media_Type_Encoder;
use Intervention\Image\Encoders\Png_Encoder;
use Intervention\Image\Encoders\Tiff_Encoder;
use Intervention\Image\Encoders\Webp_Encoder;
use Intervention\Image\Exceptions\Encoder_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Factories\Bezier_Factory;
use Intervention\Image\Geometry\Factories\Circle_Factory;
use Intervention\Image\Geometry\Factories\Ellipse_Factory;
use Intervention\Image\Geometry\Factories\Line_Factory;
use Intervention\Image\Geometry\Factories\Polygon_Factory;
use Intervention\Image\Geometry\Factories\Rectangle_Factory;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\Analyzer_Interface;
use Intervention\Image\Interfaces\Collection_Interface;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
use Intervention\Image\Interfaces\Core_Interface;
use Intervention\Image\Interfaces\Driver_Interface;
use Intervention\Image\Interfaces\Encoded_Image_Interface;
use Intervention\Image\Interfaces\Encoder_Interface;
use Intervention\Image\Interfaces\Font_Interface;
use Intervention\Image\Interfaces\Frame_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Modifier_Interface;
use Intervention\Image\Interfaces\Profile_Interface;
use Intervention\Image\Interfaces\Resolution_Interface;
use Intervention\Image\Interfaces\Size_Interface;
use Intervention\Image\Modifiers\Align_Rotation_Modifier;
use Intervention\Image\Modifiers\Blend_Transparency_Modifier;
use Intervention\Image\Modifiers\Blur_Modifier;
use Intervention\Image\Modifiers\Brightness_Modifier;
use Intervention\Image\Modifiers\Colorize_Modifier;
use Intervention\Image\Modifiers\Colorspace_Modifier;
use Intervention\Image\Modifiers\Contain_Modifier;
use Intervention\Image\Modifiers\Contrast_Modifier;
use Intervention\Image\Modifiers\Cover_Down_Modifier;
use Intervention\Image\Modifiers\Cover_Modifier;
use Intervention\Image\Modifiers\Crop_Modifier;
use Intervention\Image\Modifiers\Draw_Bezier_Modifier;
use Intervention\Image\Modifiers\Draw_Ellipse_Modifier;
use Intervention\Image\Modifiers\Draw_Line_Modifier;
use Intervention\Image\Modifiers\Draw_Pixel_Modifier;
use Intervention\Image\Modifiers\Draw_Polygon_Modifier;
use Intervention\Image\Modifiers\Draw_Rectangle_Modifier;
use Intervention\Image\Modifiers\Fill_Modifier;
use Intervention\Image\Modifiers\Flip_Modifier;
use Intervention\Image\Modifiers\Flop_Modifier;
use Intervention\Image\Modifiers\Gamma_Modifier;
use Intervention\Image\Modifiers\Greyscale_Modifier;
use Intervention\Image\Modifiers\Invert_Modifier;
use Intervention\Image\Modifiers\Pad_Modifier;
use Intervention\Image\Modifiers\Pixelate_Modifier;
use Intervention\Image\Modifiers\Place_Modifier;
use Intervention\Image\Modifiers\Profile_Modifier;
use Intervention\Image\Modifiers\Profile_Removal_Modifier;
use Intervention\Image\Modifiers\Quantize_Colors_Modifier;
use Intervention\Image\Modifiers\Remove_Animation_Modifier;
use Intervention\Image\Modifiers\Resize_Canvas_Modifier;
use Intervention\Image\Modifiers\Resize_Canvas_Relative_Modifier;
use Intervention\Image\Modifiers\Resize_Down_Modifier;
use Intervention\Image\Modifiers\Resize_Modifier;
use Intervention\Image\Modifiers\Resolution_Modifier;
use Intervention\Image\Modifiers\Rotate_Modifier;
use Intervention\Image\Modifiers\Scale_Down_Modifier;
use Intervention\Image\Modifiers\Scale_Modifier;
use Intervention\Image\Modifiers\Sharpen_Modifier;
use Intervention\Image\Modifiers\Slice_Animation_Modifier;
use Intervention\Image\Modifiers\Text_Modifier;
use Intervention\Image\Modifiers\Trim_Modifier;
use Intervention\Image\Typography\Font_Factory;
use Traversable;
final class Image implements Image_Interface
{
    /**
     * The origin from which the image was created
     */
    private Origin $origin;
    /**
     * Create new instance
     *
     * @throws RuntimeException
     */
    public function __construct(private Driver_Interface $driver, private Core_Interface $core, private Collection_Interface $exif = new Collection())
    {
        $this->origin = new Origin();
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::driver()
     */
    public function driver(): Driver_Interface
    {
        return $this->driver;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::core()
     */
    public function core(): Core_Interface
    {
        return $this->core;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::origin()
     */
    public function origin(): Origin
    {
        return $this->origin;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setOrigin()
     */
    public function set_origin(Origin $origin): Image_Interface
    {
        $this->origin = $origin;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::count()
     */
    public function count(): int
    {
        return $this->core->count();
    }
    /**
     * Implementation of IteratorAggregate
     *
     * @return Traversable<FrameInterface>
     */
    public function getIterator(): Traversable
    {
        return $this->core;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::isAnimated()
     */
    public function is_animated(): bool
    {
        return $this->count() > 1;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::removeAnimation(
     */
    public function remove_animation(int|string $position = 0): Image_Interface
    {
        return $this->modify(new Remove_Animation_Modifier($position));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::sliceAnimation()
     */
    public function slice_animation(int $offset = 0, ?int $length = null): Image_Interface
    {
        return $this->modify(new Slice_Animation_Modifier($offset, $length));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::loops()
     */
    public function loops(): int
    {
        return $this->core->loops();
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setLoops()
     */
    public function set_loops(int $loops): Image_Interface
    {
        $this->core->set_loops($loops);
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::exif()
     */
    public function exif(?string $query = null): mixed
    {
        return is_null($query) ? $this->exif : $this->exif->get($query);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setExif()
     */
    public function set_exif(Collection_Interface $exif): Image_Interface
    {
        $this->exif = $exif;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::modify()
     */
    public function modify(Modifier_Interface $modifier): Image_Interface
    {
        return $this->driver->specialize($modifier)->apply($this);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::analyze()
     */
    public function analyze(Analyzer_Interface $analyzer): mixed
    {
        return $this->driver->specialize($analyzer)->analyze($this);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encode()
     */
    public function encode(Encoder_Interface $encoder = new Auto_Encoder()): Encoded_Image_Interface
    {
        return $this->driver->specialize($encoder)->encode($this);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::save()
     */
    public function save(?string $path = null, mixed ...$options): Image_Interface
    {
        $path = is_null($path) ? $this->origin()->file_path() : $path;
        if (is_null($path)) {
            throw new Encoder_Exception('Could not determine file path to save.');
        }
        try {
            // try to determine encoding format by file extension of the path
            $encoded = $this->encode_by_path($path, ...$options);
        } catch (Encoder_Exception) {
            // fallback to encoding format by media type
            $encoded = $this->encode_by_media_type(null, ...$options);
        }
        $encoded->save($path);
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::width()
     */
    public function width(): int
    {
        return $this->analyze(new Width_Analyzer());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::height()
     */
    public function height(): int
    {
        return $this->analyze(new Height_Analyzer());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::size()
     */
    public function size(): Size_Interface
    {
        return new Rectangle($this->width(), $this->height());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::colorspace()
     */
    public function colorspace(): Colorspace_Interface
    {
        return $this->analyze(new Colorspace_Analyzer());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setColorspace()
     */
    public function set_colorspace(string|Colorspace_Interface $colorspace): Image_Interface
    {
        return $this->modify(new Colorspace_Modifier($colorspace));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resolution()
     */
    public function resolution(): Resolution_Interface
    {
        return $this->analyze(new Resolution_Analyzer());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setResolution()
     */
    public function set_resolution(float $x, float $y): Image_Interface
    {
        return $this->modify(new Resolution_Modifier($x, $y));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pickColor()
     */
    public function pick_color(int $x, int $y, int $frame_key = 0): Color_Interface
    {
        return $this->analyze(new Pixel_Color_Analyzer($x, $y, $frame_key));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pickColors()
     */
    public function pick_colors(int $x, int $y): Collection_Interface
    {
        return $this->analyze(new Pixel_Colors_Analyzer($x, $y));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::blendingColor()
     */
    public function blending_color(): Color_Interface
    {
        return $this->driver()->handle_input($this->driver()->config()->blending_color);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setBlendingColor()
     */
    public function set_blending_color(mixed $color): Image_Interface
    {
        $this->driver()->config()->set_options(blendingColor: $this->driver()->handle_input($color));
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::blendTransparency()
     */
    public function blend_transparency(mixed $color = null): Image_Interface
    {
        return $this->modify(new Blend_Transparency_Modifier($color));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::profile()
     */
    public function profile(): Profile_Interface
    {
        return $this->analyze(new Profile_Analyzer());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setProfile()
     */
    public function set_profile(Profile_Interface $profile): Image_Interface
    {
        return $this->modify(new Profile_Modifier($profile));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::removeProfile()
     */
    public function remove_profile(): Image_Interface
    {
        return $this->modify(new Profile_Removal_Modifier());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::reduceColors()
     */
    public function reduce_colors(int $limit, mixed $background = 'transparent'): Image_Interface
    {
        return $this->modify(new Quantize_Colors_Modifier($limit, $background));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::sharpen()
     */
    public function sharpen(int $amount = 10): Image_Interface
    {
        return $this->modify(new Sharpen_Modifier($amount));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::invert()
     */
    public function invert(): Image_Interface
    {
        return $this->modify(new Invert_Modifier());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pixelate()
     */
    public function pixelate(int $size): Image_Interface
    {
        return $this->modify(new Pixelate_Modifier($size));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::greyscale()
     */
    public function greyscale(): Image_Interface
    {
        return $this->modify(new Greyscale_Modifier());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::brightness()
     */
    public function brightness(int $level): Image_Interface
    {
        return $this->modify(new Brightness_Modifier($level));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::contrast()
     */
    public function contrast(int $level): Image_Interface
    {
        return $this->modify(new Contrast_Modifier($level));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::gamma()
     */
    public function gamma(float $gamma): Image_Interface
    {
        return $this->modify(new Gamma_Modifier($gamma));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::colorize()
     */
    public function colorize(int $red = 0, int $green = 0, int $blue = 0): Image_Interface
    {
        return $this->modify(new Colorize_Modifier($red, $green, $blue));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::flip()
     */
    public function flip(): Image_Interface
    {
        return $this->modify(new Flip_Modifier());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::flop()
     */
    public function flop(): Image_Interface
    {
        return $this->modify(new Flop_Modifier());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::blur()
     */
    public function blur(int $amount = 5): Image_Interface
    {
        return $this->modify(new Blur_Modifier($amount));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::rotate()
     */
    public function rotate(float $angle, mixed $background = 'ffffff'): Image_Interface
    {
        return $this->modify(new Rotate_Modifier($angle, $background));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::orient()
     */
    public function orient(): Image_Interface
    {
        return $this->modify(new Align_Rotation_Modifier());
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::text()
     */
    public function text(string $text, int $x, int $y, callable|Closure|Font_Interface $font): Image_Interface
    {
        return $this->modify(new Text_Modifier($text, new Point($x, $y), call_user_func(new Font_Factory($font))));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resize()
     */
    public function resize(?int $width = null, ?int $height = null): Image_Interface
    {
        return $this->modify(new Resize_Modifier($width, $height));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeDown()
     */
    public function resize_down(?int $width = null, ?int $height = null): Image_Interface
    {
        return $this->modify(new Resize_Down_Modifier($width, $height));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::scale()
     */
    public function scale(?int $width = null, ?int $height = null): Image_Interface
    {
        return $this->modify(new Scale_Modifier($width, $height));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::scaleDown()
     */
    public function scale_down(?int $width = null, ?int $height = null): Image_Interface
    {
        return $this->modify(new Scale_Down_Modifier($width, $height));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::cover()
     */
    public function cover(int $width, int $height, string $position = 'center'): Image_Interface
    {
        return $this->modify(new Cover_Modifier($width, $height, $position));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::coverDown()
     */
    public function cover_down(int $width, int $height, string $position = 'center'): Image_Interface
    {
        return $this->modify(new Cover_Down_Modifier($width, $height, $position));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeCanvas()
     */
    public function resize_canvas(?int $width = null, ?int $height = null, mixed $background = 'ffffff', string $position = 'center'): Image_Interface
    {
        return $this->modify(new Resize_Canvas_Modifier($width, $height, $background, $position));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeCanvasRelative()
     */
    public function resize_canvas_relative(?int $width = null, ?int $height = null, mixed $background = 'ffffff', string $position = 'center'): Image_Interface
    {
        return $this->modify(new Resize_Canvas_Relative_Modifier($width, $height, $background, $position));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::padDown()
     */
    public function pad(int $width, int $height, mixed $background = 'ffffff', string $position = 'center'): Image_Interface
    {
        return $this->modify(new Pad_Modifier($width, $height, $background, $position));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pad()
     */
    public function contain(int $width, int $height, mixed $background = 'ffffff', string $position = 'center'): Image_Interface
    {
        return $this->modify(new Contain_Modifier($width, $height, $background, $position));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::crop()
     */
    public function crop(int $width, int $height, int $offset_x = 0, int $offset_y = 0, mixed $background = 'ffffff', string $position = 'top-left'): Image_Interface
    {
        return $this->modify(new Crop_Modifier($width, $height, $offset_x, $offset_y, $background, $position));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::trim()
     */
    public function trim(int $tolerance = 0): Image_Interface
    {
        return $this->modify(new Trim_Modifier($tolerance));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::place()
     */
    public function place(mixed $element, string $position = 'top-left', int $offset_x = 0, int $offset_y = 0, int $opacity = 100): Image_Interface
    {
        return $this->modify(new Place_Modifier($element, $position, $offset_x, $offset_y, $opacity));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::fill()
     */
    public function fill(mixed $color, ?int $x = null, ?int $y = null): Image_Interface
    {
        return $this->modify(new Fill_Modifier($color, is_null($x) || is_null($y) ? null : new Point($x, $y)));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawPixel()
     */
    public function draw_pixel(int $x, int $y, mixed $color): Image_Interface
    {
        return $this->modify(new Draw_Pixel_Modifier(new Point($x, $y), $color));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawRectangle()
     */
    public function draw_rectangle(int $x, int $y, callable|Closure|Rectangle $init): Image_Interface
    {
        return $this->modify(new Draw_Rectangle_Modifier(call_user_func(new Rectangle_Factory(new Point($x, $y), $init))));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawEllipse()
     */
    public function draw_ellipse(int $x, int $y, callable|Closure|Ellipse $init): Image_Interface
    {
        return $this->modify(new Draw_Ellipse_Modifier(call_user_func(new Ellipse_Factory(new Point($x, $y), $init))));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawCircle()
     */
    public function draw_circle(int $x, int $y, callable|Closure|Circle $init): Image_Interface
    {
        return $this->modify(new Draw_Ellipse_Modifier(call_user_func(new Circle_Factory(new Point($x, $y), $init))));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawPolygon()
     */
    public function draw_polygon(callable|Closure|Polygon $init): Image_Interface
    {
        return $this->modify(new Draw_Polygon_Modifier(call_user_func(new Polygon_Factory($init))));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawLine()
     */
    public function draw_line(callable|Closure|Line $init): Image_Interface
    {
        return $this->modify(new Draw_Line_Modifier(call_user_func(new Line_Factory($init))));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawBezier()
     */
    public function draw_bezier(callable|Closure|Bezier $init): Image_Interface
    {
        return $this->modify(new Draw_Bezier_Modifier(call_user_func(new Bezier_Factory($init))));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeByMediaType()
     */
    public function encode_by_media_type(null|string|Media_Type $type = null, mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Media_Type_Encoder($type, ...$options));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeByExtension()
     */
    public function encode_by_extension(null|string|File_Extension $extension = null, mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new File_Extension_Encoder($extension, ...$options));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeByPath()
     */
    public function encode_by_path(?string $path = null, mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new File_Path_Encoder($path, ...$options));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toJpeg()
     */
    public function to_jpeg(mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Jpeg_Encoder(...$options));
    }
    /**
     * Alias of self::toJpeg()
     *
     * @throws RuntimeException
     */
    public function to_jpg(mixed ...$options): Encoded_Image_Interface
    {
        return $this->to_jpeg(...$options);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toJpeg()
     */
    public function to_jpeg2000(mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Jpeg2000Encoder(...$options));
    }
    /**
     * ALias of self::toJpeg2000()
     *
     * @throws RuntimeException
     */
    public function to_jp2(mixed ...$options): Encoded_Image_Interface
    {
        return $this->to_jpeg2000(...$options);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toPng()
     */
    public function to_png(mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Png_Encoder(...$options));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toGif()
     */
    public function to_gif(mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Gif_Encoder(...$options));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toWebp()
     */
    public function to_webp(mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Webp_Encoder(...$options));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toBitmap()
     */
    public function to_bitmap(mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Bmp_Encoder(...$options));
    }
    /**
     * Alias if self::toBitmap()
     *
     * @throws RuntimeException
     */
    public function to_bmp(mixed ...$options): Encoded_Image_Interface
    {
        return $this->to_bitmap(...$options);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toAvif()
     */
    public function to_avif(mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Avif_Encoder(...$options));
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toTiff()
     */
    public function to_tiff(mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Tiff_Encoder(...$options));
    }
    /**
     * Alias of self::toTiff()
     *
     * @throws RuntimeException
     */
    public function to_tif(mixed ...$options): Encoded_Image_Interface
    {
        return $this->to_tiff(...$options);
    }
    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toHeic()
     */
    public function to_heic(mixed ...$options): Encoded_Image_Interface
    {
        return $this->encode(new Heic_Encoder(...$options));
    }
    /**
     * Show debug info for the current image
     *
     * @return array<string, int>
     */
    public function __debugInfo(): array
    {
        try {
            return ['width' => $this->width(), 'height' => $this->height()];
        } catch (RuntimeException) {
            return [];
        }
    }
    /**
     * Clone image
     */
    public function __clone(): void
    {
        $this->driver = clone $this->driver;
        $this->core = clone $this->core;
        $this->exif = clone $this->exif;
    }
}