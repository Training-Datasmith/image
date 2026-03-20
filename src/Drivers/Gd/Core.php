<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Exceptions\Animation_Exception;
use Intervention\Image\Interfaces\Core_Interface;
use Intervention\Image\Interfaces\Frame_Interface;
class Core extends Collection implements Core_Interface
{
    protected int $loops = 0;
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::add()
     */
    public function add(Frame_Interface $frame): Core_Interface
    {
        $this->push($frame);
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::native()
     */
    public function native(): mixed
    {
        return $this->first()->native();
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::setNative()
     */
    public function set_native(mixed $native): self
    {
        $this->empty()->push(new Frame($native));
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::frame()
     */
    public function frame(int $position): Frame_Interface
    {
        $frame = $this->get_at_position($position);
        if (!$frame instanceof Frame_Interface) {
            throw new Animation_Exception('Frame #' . $position . ' could not be found in the image.');
        }
        return $frame;
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::loops()
     */
    public function loops(): int
    {
        return $this->loops;
    }
    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::setLoops()
     */
    public function set_loops(int $loops): self
    {
        $this->loops = $loops;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::first()
     */
    public function first(): Frame_Interface
    {
        return parent::first();
    }
    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::last()
     */
    public function last(): Frame_Interface
    {
        return parent::last();
    }
    /**
     * Clone instance
     */
    public function __clone(): void
    {
        foreach ($this->items as $key => $frame) {
            $this->items[$key] = clone $frame;
        }
    }
}