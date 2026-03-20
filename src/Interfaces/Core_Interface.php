<?php

declare (strict_types=1);
namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\Animation_Exception;
interface Core_Interface extends Collection_Interface
{
    /**
     * return driver's representation of the image core.
     *
     * @throws AnimationException
     */
    public function native(): mixed;
    /**
     * Set driver's representation of the image core.
     *
     * @return CoreInterface<FrameInterface>
     */
    public function set_native(mixed $native): self;
    /**
     * Count number of frames of animated image core
     */
    public function count(): int;
    /**
     * Return frame of given position in an animated image
     *
     * @throws AnimationException
     */
    public function frame(int $position): Frame_Interface;
    /**
     * Add new frame to core
     *
     * @return CoreInterface<FrameInterface>
     */
    public function add(Frame_Interface $frame): self;
    /**
     * Return number of repetitions of an animated image
     */
    public function loops(): int;
    /**
     * Set the number of repetitions for an animation. Where a
     * value of 0 means infinite repetition.
     *
     * @return CoreInterface<FrameInterface>
     */
    public function set_loops(int $loops): self;
    /**
     * Get first frame in core
     *
     * @throws AnimationException
     */
    public function first(): Frame_Interface;
    /**
     * Get last frame in core
     *
     * @throws AnimationException
     */
    public function last(): Frame_Interface;
}