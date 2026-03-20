<?php

declare (strict_types=1);
namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\Driver_Exception;
use Intervention\Image\Interfaces\Driver_Interface;
use Intervention\Image\Interfaces\Specializable_Interface;
use ReflectionClass;
trait Can_Be_Driver_Specialized
{
    /**
     * The driver with which the instance may be specialized
     */
    protected Driver_Interface $driver;
    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::specializable()
     */
    public function specializable(): array
    {
        $specializable = [];
        $reflection_class = new ReflectionClass($this::class);
        if ($constructor = $reflection_class->get_constructor()) {
            foreach ($constructor->get_parameters() as $parameter) {
                $specializable[$parameter->get_name()] = $this->{$parameter->get_name()};
            }
        }
        return $specializable;
    }
    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::driver()
     */
    public function driver(): Driver_Interface
    {
        return $this->driver;
    }
    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::setDriver()
     */
    public function set_driver(Driver_Interface $driver): Specializable_Interface
    {
        if (!$this->belongs_to_driver($driver)) {
            throw new Driver_Exception("Class '" . $this::class . "' can not be used with " . $driver->id() . ' driver.');
        }
        $this->driver = $driver;
        return $this;
    }
    /**
     * Determine if the current object belongs to the given driver's namespace
     */
    protected function belongs_to_driver(object $driver): bool
    {
        $namespace = fn(object $object): string => (new ReflectionClass($object))->get_namespace_name();
        return str_starts_with($namespace($this), $namespace($driver));
    }
}