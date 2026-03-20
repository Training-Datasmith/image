<?php

declare (strict_types=1);
namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Color_Channel_Interface;
use Intervention\Image\Interfaces\Color_Interface;
use Intervention\Image\Interfaces\Colorspace_Interface;
use ReflectionClass;
use Stringable;
abstract class Abstract_Color implements Color_Interface, Stringable
{
    /**
     * Color channels
     *
     * @var array<ColorChannelInterface>
     */
    protected array $channels;
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::channels()
     */
    public function channels(): array
    {
        return $this->channels;
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::channel()
     */
    public function channel(string $classname): Color_Channel_Interface
    {
        $channels = array_filter($this->channels(), fn(Color_Channel_Interface $channel): bool => $channel::class === $classname);
        if (count($channels) == 0) {
            throw new Color_Exception('Color channel ' . $classname . ' could not be found.');
        }
        return reset($channels);
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::normalize()
     */
    public function normalize(): array
    {
        return array_map(fn(Color_Channel_Interface $channel): float => $channel->normalize(), $this->channels());
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toArray()
     */
    public function to_array(): array
    {
        return array_map(fn(Color_Channel_Interface $channel): int => $channel->value(), $this->channels());
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::convertTo()
     */
    public function convert_to(string|Colorspace_Interface $colorspace): Color_Interface
    {
        $colorspace = match (true) {
            is_object($colorspace) => $colorspace,
            default => new $colorspace(),
        };
        return $colorspace->import_color($this);
    }
    /**
     * Show debug info for the current color
     *
     * @return array<string, int>
     */
    public function __debugInfo(): array
    {
        return array_reduce($this->channels(), function (array $result, Color_Channel_Interface $item): array {
            $key = strtolower((new ReflectionClass($item))->get_short_name());
            $result[$key] = $item->value();
            return $result;
        }, []);
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::__toString()
     */
    public function __toString(): string
    {
        return $this->to_string();
    }
}