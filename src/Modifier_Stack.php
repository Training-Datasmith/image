<?php

declare (strict_types=1);
namespace Intervention\Image;

use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Modifier_Interface;
class Modifier_Stack implements Modifier_Interface
{
    /**
     * Create new modifier stack object with an array of modifier objects
     *
     * @param array<ModifierInterface> $modifiers
     */
    public function __construct(protected array $modifiers)
    {
    }
    /**
     * Apply all modifiers in stack to the given image
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($this->modifiers as $modifier) {
            $modifier->apply($image);
        }
        return $image;
    }
    /**
     * Append new modifier to the stack
     */
    public function push(Modifier_Interface $modifier): self
    {
        $this->modifiers[] = $modifier;
        return $this;
    }
}