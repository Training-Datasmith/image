<?php

declare (strict_types=1);
namespace Intervention\Image;

use Intervention\Image\Colors\Cmyk\Decoders\String_Color_Decoder as CmykStringColorDecoder;
use Intervention\Image\Colors\Hsl\Decoders\String_Color_Decoder as HslStringColorDecoder;
use Intervention\Image\Colors\Hsv\Decoders\String_Color_Decoder as HsvStringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\Hex_Color_Decoder as RgbHexColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\Html_Colorname_Decoder;
use Intervention\Image\Colors\Rgb\Decoders\String_Color_Decoder as RgbStringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\Transparent_Color_Decoder;
use Intervention\Image\Decoders\Base64image_Decoder;
use Intervention\Image\Decoders\Binary_Image_Decoder;
use Intervention\Image\Decoders\Color_Object_Decoder;
use Intervention\Image\Decoders\Data_Uri_Image_Decoder;
use Intervention\Image\Decoders\Encoded_Image_Object_Decoder;
use Intervention\Image\Decoders\File_Path_Image_Decoder;
use Intervention\Image\Decoders\File_Pointer_Image_Decoder;
use Intervention\Image\Decoders\Image_Object_Decoder;
use Intervention\Image\Decoders\Native_Object_Decoder;
use Intervention\Image\Decoders\Spl_File_Info_Image_Decoder;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Exceptions\Driver_Exception;
use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Driver_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Input_Handler_Interface;
class Input_Handler implements Input_Handler_Interface
{
    /**
     * Decoder classnames in hierarchical order
     *
     * @var array<string|DecoderInterface>
     */
    protected array $decoders = [Native_Object_Decoder::class, Image_Object_Decoder::class, Color_Object_Decoder::class, Rgb_Hex_Color_Decoder::class, Rgb_String_Color_Decoder::class, Cmyk_String_Color_Decoder::class, Hsv_String_Color_Decoder::class, Hsl_String_Color_Decoder::class, Transparent_Color_Decoder::class, Html_Colorname_Decoder::class, File_Pointer_Image_Decoder::class, File_Path_Image_Decoder::class, Spl_File_Info_Image_Decoder::class, Binary_Image_Decoder::class, Data_Uri_Image_Decoder::class, Base64image_Decoder::class, Encoded_Image_Object_Decoder::class];
    /**
     * Create new input handler instance with given decoder classnames
     *
     * @param array<string|DecoderInterface> $decoders
     */
    public function __construct(
        array $decoders = [],
        /**
         * Driver with which the decoder classes are specialized
         */
        protected ?Driver_Interface $driver = null
    )
    {
        $this->decoders = count($decoders) ? $decoders : $this->decoders;
    }
    /**
     * Static factory method
     *
     * @param array<string|DecoderInterface> $decoders
     */
    public static function with_decoders(array $decoders, ?Driver_Interface $driver = null): self
    {
        return new self($decoders, $driver);
    }
    /**
     * {@inheritdoc}
     *
     * @see InputHandlerInterface::handle()
     */
    public function handle(mixed $input): Image_Interface|Color_Interface
    {
        foreach ($this->decoders as $decoder) {
            try {
                // decode with driver specialized decoder
                return $this->resolve($decoder)->decode($input);
            } catch (Decoder_Exception|Not_Supported_Exception) {
                // try next decoder
            }
        }
        if (isset($e)) {
            throw new ($e::class)($e->get_message());
        }
        throw new Decoder_Exception('Unable to decode input.');
    }
    /**
     * Resolve the given classname to an decoder object
     *
     * @throws DriverException
     * @throws NotSupportedException
     */
    private function resolve(string|Decoder_Interface $decoder): Decoder_Interface
    {
        if ($decoder instanceof Decoder_Interface && !$this->driver instanceof \Intervention\Image\Interfaces\Driver_Interface) {
            return $decoder;
        }
        if ($decoder instanceof Decoder_Interface && $this->driver instanceof \Intervention\Image\Interfaces\Driver_Interface) {
            return $this->driver->specialize($decoder);
        }
        if (!$this->driver instanceof \Intervention\Image\Interfaces\Driver_Interface) {
            return new $decoder();
        }
        return $this->driver->specialize(new $decoder());
    }
}