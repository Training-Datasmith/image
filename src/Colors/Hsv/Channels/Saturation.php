<?php

declare (strict_types=1);
namespace Intervention\Image\Colors\Hsv\Channels;

use Intervention\Image\Colors\Abstract_Color_Channel;
class Saturation extends Abstract_Color_Channel
{
    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::min()
     */
    public function min(): int
    {
        return 0;
    }
    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::max()
     */
    public function max(): int
    {
        return 100;
    }
}