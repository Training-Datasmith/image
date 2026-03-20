<?php

declare (strict_types=1);
namespace Intervention\Image;

use Error;
use Intervention\Image\Encoders\Avif_Encoder;
use Intervention\Image\Encoders\Bmp_Encoder;
use Intervention\Image\Encoders\Gif_Encoder;
use Intervention\Image\Encoders\Heic_Encoder;
use Intervention\Image\Encoders\Jpeg2000Encoder;
use Intervention\Image\Encoders\Jpeg_Encoder;
use Intervention\Image\Encoders\Png_Encoder;
use Intervention\Image\Encoders\Tiff_Encoder;
use Intervention\Image\Encoders\Webp_Encoder;
use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Interfaces\Encoder_Interface;
use ReflectionClass;
use ReflectionParameter;
enum Format
{
    case AVIF;
    case BMP;
    case GIF;
    case HEIC;
    case JP2;
    case JPEG;
    case PNG;
    case TIFF;
    case WEBP;
    /**
     * Create format from given identifier
     *
     * @param string|Format|MediaType|FileExtension $identifier
     * @throws NotSupportedException
     */
    public static function create(string|self|Media_Type|File_Extension $identifier): self
    {
        if ($identifier instanceof self) {
            return $identifier;
        }
        if ($identifier instanceof Media_Type) {
            return $identifier->format();
        }
        if ($identifier instanceof File_Extension) {
            return $identifier->format();
        }
        try {
            $format = Media_Type::from(strtolower($identifier))->format();
        } catch (Error) {
            try {
                $format = File_Extension::from(strtolower($identifier))->format();
            } catch (Error) {
                throw new Not_Supported_Exception('Unable to create format from "' . $identifier . '".');
            }
        }
        return $format;
    }
    /**
     * Try to create format from given identifier and return null on failure
     *
     * @param string|Format|MediaType|FileExtension $identifier
     * @return Format|null
     */
    public static function try_create(string|self|Media_Type|File_Extension $identifier): ?self
    {
        try {
            return self::create($identifier);
        } catch (Not_Supported_Exception) {
            return null;
        }
    }
    /**
     * Return the possible media (MIME) types for the current format
     *
     * @return array<MediaType>
     */
    public function media_types(): array
    {
        return array_filter(Media_Type::cases(), fn(Media_Type $media_type): bool => $media_type->format() === $this);
    }
    /**
     * Return the first found media type for the current format
     */
    public function media_type(): Media_Type
    {
        $types = $this->media_types();
        return reset($types);
    }
    /**
     * Return the possible file extension for the current format
     *
     * @return array<FileExtension>
     */
    public function file_extensions(): array
    {
        return array_filter(File_Extension::cases(), fn(File_Extension $file_extension): bool => $file_extension->format() === $this);
    }
    /**
     * Return the first found file extension for the current format
     */
    public function file_extension(): File_Extension
    {
        $extensions = $this->file_extensions();
        return reset($extensions);
    }
    /**
     * Create an encoder instance with given options that matches the format
     */
    public function encoder(mixed ...$options): Encoder_Interface
    {
        // get classname of target encoder from current format
        $classname = match ($this) {
            self::AVIF => Avif_Encoder::class,
            self::BMP => Bmp_Encoder::class,
            self::GIF => Gif_Encoder::class,
            self::HEIC => Heic_Encoder::class,
            self::JP2 => Jpeg2000Encoder::class,
            self::JPEG => Jpeg_Encoder::class,
            self::PNG => Png_Encoder::class,
            self::TIFF => Tiff_Encoder::class,
            self::WEBP => Webp_Encoder::class,
        };
        // get parameters of target encoder
        $parameters = [];
        $reflection_class = new ReflectionClass($classname);
        if ($constructor = $reflection_class->get_constructor()) {
            $parameters = array_map(fn(ReflectionParameter $parameter): string => $parameter->get_name(), $constructor->get_parameters());
        }
        // filter out unavailable options of target encoder
        $options = array_filter($options, fn(mixed $key): bool => in_array($key, $parameters), ARRAY_FILTER_USE_KEY);
        return new $classname(...$options);
    }
}