<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Decoders;

use Exception;
use Gd_Image;
use Intervention\Gif\Decoder as GifDecoder;
use Intervention\Gif\Splitter as GifSplitter;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Exceptions\Decoder_Exception;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Image_Interface;
class Native_Object_Decoder extends Abstract_Decoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): Image_Interface|Color_Interface
    {
        if (!is_object($input)) {
            throw new Decoder_Exception('Unable to decode input');
        }
        if (!$input instanceof Gd_Image) {
            throw new Decoder_Exception('Unable to decode input');
        }
        if (!imageistruecolor($input)) {
            imagepalettetotruecolor($input);
        }
        imagesavealpha($input, true);
        // build image instance
        return new Image($this->driver(), new Core([new Frame($input)]));
    }
    /**
     * Decode image from given GIF source which can be either a file path or binary data
     *
     * Depending on the configuration, this is taken over by the native GD function
     * or, if animations are required, by our own extended decoder.
     *
     * @throws RuntimeException
     */
    protected function decode_gif(mixed $input): Image_Interface
    {
        // create non-animated image depending on config
        if (!$this->driver()->config()->decode_animation) {
            $native = match (true) {
                $this->is_gif_format($input) => @imagecreatefromstring($input),
                default => @imagecreatefromgif($input),
            };
            if ($native === false) {
                throw new Decoder_Exception('Unable to decode input.');
            }
            $image = self::decode($native);
            $image->origin()->set_media_type('image/gif');
            return $image;
        }
        try {
            // create empty core
            $core = new Core();
            $gif = Gif_Decoder::decode($input);
            $splitter = Gif_Splitter::create($gif)->split();
            $delays = $splitter->get_delays();
            // set loops on core
            if ($loops = $gif->get_main_application_extension()?->get_loops()) {
                $core->set_loops($loops);
            }
            // add GDImage instances to core
            foreach ($splitter->coalesce_to_resources() as $key => $native) {
                $core->push(new Frame($native, $delays[$key] / 100));
            }
        } catch (Exception $e) {
            throw new Decoder_Exception($e->get_message(), $e->get_code(), $e);
        }
        // create (possibly) animated image
        $image = new Image($this->driver(), $core);
        // set media type
        $image->origin()->set_media_type('image/gif');
        return $image;
    }
}