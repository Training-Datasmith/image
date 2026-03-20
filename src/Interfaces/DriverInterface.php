<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Config;
use Intervention\Image\Exceptions\Driver_Exception;
use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\File_Extension;
use Intervention\Image\Format;
use Intervention\Image\Media_Type;
interface Driver_Interface
{
    /**
     * Return drivers unique id
     */
    public function id(): string;
    /**
     * Get driver configuration
     */
    public function config(): Config;
    /**
     * Resolve given (generic) object into a specialized version for the current driver
     *
     * @throws NotSupportedException
     * @throws DriverException
     */
    public function specialize(Modifier_Interface|Analyzer_Interface|Encoder_Interface|Decoder_Interface $object): Modifier_Interface|Analyzer_Interface|Encoder_Interface|Decoder_Interface;
    /**
     * Resolve array of classnames or objects into their specialized version for the current driver
     *
     * @param array<string|object> $objects
     * @throws NotSupportedException
     * @throws DriverException
     * @return array<object>
     */
    public function specialize_multiple(array $objects): array;
    /**
     * Create new image instance with the current driver in given dimensions
     *
     * @throws RuntimeException
     */
    public function create_image(int $width, int $height): Image_Interface;
    /**
     * Create new animated image
     *
     * @throws RuntimeException
     */
    public function create_animation(callable $init): Image_Interface;
    /**
     * Handle given input by decoding it to ImageInterface or ColorInterface
     *
     * @param array<string|DecoderInterface> $decoders
     * @throws RuntimeException
     */
    public function handle_input(mixed $input, array $decoders = []): Image_Interface|Color_Interface;
    /**
     * Return color processor for the given colorspace
     */
    public function color_processor(Colorspace_Interface $colorspace): Color_Processor_Interface;
    /**
     * Return font processor of the current driver
     */
    public function font_processor(): Font_Processor_Interface;
    /**
     * Check whether all requirements for operating the driver are met and
     * throw exception if the check fails.
     *
     * @throws DriverException
     */
    public function check_health(): void;
    /**
     * Check if the current driver supports the given format and if the
     * underlying PHP extension was built with support for the format.
     */
    public function supports(string|Format|File_Extension|Media_Type $identifier): bool;
}