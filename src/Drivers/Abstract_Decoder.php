<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers;

use Exception;
use Intervention\Image\Collection;
use Intervention\Image\Interfaces\Collection_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Traits\Can_Build_File_Pointer;
abstract class Abstract_Decoder implements Decoder_Interface
{
    use Can_Build_File_Pointer;
    /**
     * Determine if the given input is GIF data format
     */
    protected function is_gif_format(string $input): bool
    {
        return str_starts_with($input, 'GIF87a') || str_starts_with($input, 'GIF89a');
    }
    /**
     * Determine if given input is a path to an existing regular file
     */
    protected function is_file(mixed $input): bool
    {
        if (!is_string($input)) {
            return false;
        }
        if (strlen($input) > PHP_MAXPATHLEN) {
            return false;
        }
        try {
            if (!@is_file($input)) {
                return false;
            }
        } catch (Exception) {
            return false;
        }
        return true;
    }
    /**
     * Extract and return EXIF data from given input which can be binary image
     * data or a file path.
     *
     * @return CollectionInterface<string, mixed>
     */
    protected function extract_exif_data(string $path_or_data): Collection_Interface
    {
        if (!function_exists('exif_read_data')) {
            return new Collection();
        }
        try {
            $source = match (true) {
                $this->is_file($path_or_data) => $path_or_data,
                // path
                default => $this->build_file_pointer($path_or_data),
            };
            // extract exif data
            $data = @exif_read_data($source, null, true);
            if (is_resource($source)) {
                fclose($source);
            }
        } catch (Exception) {
            $data = [];
        }
        return new Collection(is_array($data) ? $data : []);
    }
    /**
     * Determine if given input is base64 encoded data
     */
    protected function is_valid_base64(mixed $input): bool
    {
        if (!is_string($input)) {
            return false;
        }
        return base64_encode(base64_decode($input)) === str_replace(["\n", "\r"], '', $input);
    }
    /**
     * Parse data uri
     */
    protected function parse_data_uri(mixed $input): object
    {
        // The data portion is bounded to 15 MB of base64 text (~11 MB decoded) to prevent ReDoS
        // on unbounded (.*) back-tracking against very long non-matching strings.
        $pattern = "/^data:(?P<mediatype>\\w+\\/[-+.\\w]+)?" . "(?P<parameters>(;[-\\w]+=[-\\w]+)*)(?P<base64>;base64)?,(?P<data>.{0,20971520}+)/s";
        $result = preg_match($pattern, (string) $input, $matches);
        return new class($matches, $result)
        {
            /**
             * @param array<mixed> $matches
             */
            public function __construct(private array $matches, private readonly int|false $result)
            {
            }
            public function is_valid(): bool
            {
                return (bool) $this->result;
            }
            public function media_type(): ?string
            {
                if (isset($this->matches['mediatype']) && !empty($this->matches['mediatype'])) {
                    return $this->matches['mediatype'];
                }
                return null;
            }
            public function has_media_type(): bool
            {
                return !empty($this->media_type());
            }
            public function is_base64encoded(): bool
            {
                return isset($this->matches['base64']) && $this->matches['base64'] === ';base64';
            }
            public function data(): ?string
            {
                if (isset($this->matches['data']) && !empty($this->matches['data'])) {
                    return $this->matches['data'];
                }
                return null;
            }
        };
    }
}