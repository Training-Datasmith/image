<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use Imagick_Exception;
use Intervention\Image\Exceptions\Animation_Exception;
use Intervention\Image\Interfaces\Collection_Interface;
use Intervention\Image\Interfaces\Core_Interface;
use Intervention\Image\Interfaces\Frame_Interface;
use Iterator;
/**
 * @implements Iterator<FrameInterface>
 */
class Core implements Core_Interface, Iterator
{
    protected int $iterator_index = 0;
    /**
     * Create new core instance
     */
    public function __construct(protected Imagick $imagick)
    {
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::has()
     */
    public function has(int|string $key): bool
    {
        try {
            $result = $this->imagick->set_iterator_index($key);
        } catch (Imagick_Exception) {
            return false;
        }
        return $result;
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::push()
     */
    public function push(mixed $item): Collection_Interface
    {
        return $this->add($item);
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::get()
     */
    public function get(int|string $key, mixed $default = null): mixed
    {
        try {
            $this->imagick->set_iterator_index($key);
        } catch (Imagick_Exception) {
            return $default;
        }
        return new Frame($this->imagick->current());
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::getAtPosition()
     */
    public function get_at_position(int $key = 0, mixed $default = null): mixed
    {
        return $this->get($key, $default);
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::empty()
     */
    public function empty(): Collection_Interface
    {
        $this->imagick->clear();
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::slice()
     */
    public function slice(int $offset, ?int $length = null): Collection_Interface
    {
        $allowed_indexes = [];
        $length = is_null($length) ? $this->count() : $length;
        for ($i = $offset; $i < $offset + $length; $i++) {
            $allowed_indexes[] = $i;
        }
        $sliced = new Imagick();
        foreach ($this->imagick as $key => $native) {
            if (in_array($key, $allowed_indexes)) {
                $sliced->add_image($native->get_image());
            }
        }
        $sliced = $sliced->coalesce_images();
        $sliced->set_image_iterations($this->imagick->get_image_iterations());
        $this->imagick = $sliced;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::add()
     */
    public function add(Frame_Interface $frame): Core_Interface
    {
        $imagick = $frame->native();
        $imagick->set_image_delay((int) round($frame->delay() * 100));
        $imagick->set_image_dispose($frame->dispose());
        $size = $frame->size();
        $imagick->set_image_page($size->width(), $size->height(), $frame->offset_left(), $frame->offset_top());
        $this->imagick->add_image($imagick);
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::count()
     */
    public function count(): int
    {
        return $this->imagick->get_number_images();
    }
    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function current(): mixed
    {
        $this->imagick->set_iterator_index($this->iterator_index);
        return new Frame($this->imagick->current());
    }
    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function next(): void
    {
        $this->iterator_index += 1;
    }
    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function key(): mixed
    {
        return $this->iterator_index;
    }
    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function valid(): bool
    {
        try {
            $result = $this->imagick->set_iterator_index($this->iterator_index);
        } catch (Imagick_Exception) {
            return false;
        }
        return $result;
    }
    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function rewind(): void
    {
        $this->iterator_index = 0;
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::native()
     */
    public function native(): mixed
    {
        return $this->imagick;
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::setNative()
     */
    public function set_native(mixed $native): Core_Interface
    {
        $this->imagick = $native;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::frame()
     */
    public function frame(int $position): Frame_Interface
    {
        foreach ($this->imagick as $core) {
            if ($core->get_iterator_index() === $position) {
                return new Frame($core);
            }
        }
        throw new Animation_Exception('Frame #' . $position . ' could not be found in the image.');
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::loops()
     */
    public function loops(): int
    {
        return $this->imagick->get_image_iterations();
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::setLoops()
     */
    public function set_loops(int $loops): Core_Interface
    {
        $this->imagick = $this->imagick->coalesce_images();
        $this->imagick->set_image_iterations($loops);
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::first()
     */
    public function first(): Frame_Interface
    {
        return $this->frame(0);
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectableInterface::last()
     */
    public function last(): Frame_Interface
    {
        return $this->frame($this->count() - 1);
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::toArray()
     */
    public function to_array(): array
    {
        $frames = [];
        foreach ($this as $frame) {
            $frames[] = $frame;
        }
        return $frames;
    }
    /**
     * Clone instance
     */
    public function __clone(): void
    {
        $this->imagick = clone $this->imagick;
    }
}