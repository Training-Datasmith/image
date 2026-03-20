<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry\Traits;

trait Has_Border
{
    protected mixed $border_color = null;
    protected int $border_size = 0;
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBorder()
     */
    public function set_border(mixed $color, int $size = 1): self
    {
        return $this->set_border_size($size)->set_border_color($color);
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBorderSize()
     */
    public function set_border_size(int $size): self
    {
        $this->border_size = $size;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::borderSize()
     */
    public function border_size(): int
    {
        return $this->border_size;
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBorderColor()
     */
    public function set_border_color(mixed $color): self
    {
        $this->border_color = $color;
        return $this;
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::borderColor()
     */
    public function border_color(): mixed
    {
        return $this->border_color;
    }
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::hasBorder()
     */
    public function has_border(): bool
    {
        return $this->border_size > 0 && !is_null($this->border_color);
    }
}