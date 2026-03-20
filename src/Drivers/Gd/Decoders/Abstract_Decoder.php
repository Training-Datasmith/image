<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Specializable_Decoder;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Media_Type;
use Value_Error;
abstract class Abstract_Decoder extends Specializable_Decoder implements Specialized_Interface
{
    /**
     * Return media (mime) type of the file at given file path
     *
     * @throws DecoderException
     * @throws NotSupportedException
     */
    protected function get_media_type_by_file_path(string $filepath): Media_Type
    {
        if (function_exists('finfo_file') && function_exists('finfo_open')) {
            $media_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filepath);
            if (is_string($media_type)) {
                try {
                    return Media_Type::from($media_type);
                } catch (Value_Error) {
                    throw new Not_Supported_Exception('Unsupported media type (MIME) ' . $media_type . '.');
                }
            }
        }
        $info = @getimagesize($filepath);
        if (!is_array($info)) {
            throw new Decoder_Exception('Unable to detect media (MIME) from data in file path.');
        }
        try {
            return Media_Type::from($info['mime']);
        } catch (Value_Error) {
            throw new Not_Supported_Exception('Unsupported media type (MIME) ' . $info['mime'] . '.');
        }
    }
    /**
     * Return media (mime) type of the given image data
     *
     * @throws DecoderException
     * @throws NotSupportedException
     */
    protected function get_media_type_by_binary(string $data): Media_Type
    {
        if (function_exists('finfo_buffer') && function_exists('finfo_open')) {
            $media_type = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $data);
            if (is_string($media_type)) {
                try {
                    return Media_Type::from($media_type);
                } catch (Value_Error) {
                    throw new Not_Supported_Exception('Unsupported media type (MIME) ' . $media_type . '.');
                }
            }
        }
        $info = @getimagesizefromstring($data);
        if (!is_array($info)) {
            throw new Decoder_Exception('Unable to detect media (MIME) from binary data.');
        }
        try {
            return Media_Type::from($info['mime']);
        } catch (Value_Error) {
            throw new Not_Supported_Exception('Unsupported media type (MIME) ' . $info['mime'] . '.');
        }
    }
}