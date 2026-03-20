<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry\Traits;

trait Has_Background_Color
{
    protected mixed $background_color = null;
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBackgroundColor()
     */
    public function set_background_color(mixed $color): self
    {
        $this->background_color = $color;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::backgroundColor()
     */
    public function background_color(): mixed
    {
        return $this->background_color;
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::hasBackgroundColor()
     */
    public function has_background_color(): bool
    {
        return !empty($this->background_color);
    }
}