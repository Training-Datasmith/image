<?php

declare (strict_types=1);
namespace Intervention\Image;

use Intervention\Image\Exceptions\Input_Exception;
class Config
{
    /**
     * Create config object instance
     */
    public function __construct(public bool $auto_orientation = true, public bool $decode_animation = true, public mixed $blending_color = 'ffffff', public bool $strip = false)
    {
    }
    /**
     * Set values of given config options
     *
     * @throws InputException
     */
    public function set_options(mixed ...$options): self
    {
        foreach ($this->prepare_options($options) as $name => $value) {
            if (!property_exists($this, $name)) {
                throw new Input_Exception('Property ' . $name . ' does not exists for ' . static::class . '.');
            }
            $this->{$name} = $value;
        }
        return $this;
    }
    /**
     * This method makes it possible to call self::setOptions() with a single
     * array instead of named parameters
     *
     * @param array<mixed> $options
     * @return array<string, mixed>
     */
    private function prepare_options(array $options): array
    {
        if ($options === []) {
            return $options;
        }
        if (count($options) > 1) {
            return $options;
        }
        if (!array_key_exists(0, $options)) {
            return $options;
        }
        if (!is_array($options[0])) {
            return $options;
        }
        return $options[0];
    }
}