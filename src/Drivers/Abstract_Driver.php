<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers;

use Intervention\Image\Config;
use Intervention\Image\Exceptions\Driver_Exception;
use Intervention\Image\Exceptions\Not_Supported_Exception;
use Intervention\Image\Input_Handler;
use Intervention\Image\Interfaces\Analyzer_Interface;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Decoder_Interface;
use Intervention\Image\Interfaces\Driver_Interface;
use Intervention\Image\Interfaces\Encoder_Interface;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Modifier_Interface;
use Intervention\Image\Interfaces\Specializable_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use ReflectionClass;
abstract class Abstract_Driver implements Driver_Interface
{
    /**
     * Driver options
     */
    protected Config $config;
    /**
     * @throws DriverException
     */
    public function __construct()
    {
        $this->config = new Config();
        $this->check_health();
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::config()
     */
    public function config(): Config
    {
        return $this->config;
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::handleInput()
     */
    public function handle_input(mixed $input, array $decoders = []): Image_Interface|Color_Interface
    {
        return Input_Handler::with_decoders($decoders, $this)->handle($input);
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specialize()
     */
    public function specialize(Modifier_Interface|Analyzer_Interface|Encoder_Interface|Decoder_Interface $object): Modifier_Interface|Analyzer_Interface|Encoder_Interface|Decoder_Interface
    {
        // return object directly if no specializing is possible
        if (!$object instanceof Specializable_Interface) {
            return $object;
        }
        // return directly and only attach driver if object is already specialized
        if ($object instanceof Specialized_Interface) {
            $object->set_driver($this);
            return $object;
        }
        // resolve classname for specializable object
        $specialized_classname = implode('\\', [
            (new ReflectionClass($this))->get_namespace_name(),
            // driver's namespace
            match (true) {
                $object instanceof Modifier_Interface => 'Modifiers',
                $object instanceof Analyzer_Interface => 'Analyzers',
                $object instanceof Encoder_Interface => 'Encoders',
                $object instanceof Decoder_Interface => 'Decoders',
            },
            $object_shortname = (new ReflectionClass($object))->get_short_name(),
        ]);
        // fail if driver specialized classname does not exists
        if (!class_exists($specialized_classname)) {
            throw new Not_Supported_Exception("Class '" . $object_shortname . "' is not supported by " . $this->id() . ' driver.');
        }
        // create a driver specialized object with the specializable properties of generic object
        $specialized = new $specialized_classname(...$object->specializable());
        // attach driver
        return $specialized->set_driver($this);
    }
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specializeMultiple()
     *
     * @throws NotSupportedException
     * @throws DriverException
     */
    public function specialize_multiple(array $objects): array
    {
        return array_map(fn(string|object $object): Modifier_Interface|Analyzer_Interface|Encoder_Interface|Decoder_Interface => $this->specialize(match (true) {
            is_string($object) => new $object(),
            is_object($object) => $object,
        }), $objects);
    }
}