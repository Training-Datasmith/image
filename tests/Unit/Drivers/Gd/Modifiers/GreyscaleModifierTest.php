<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\GreyscaleModifier;
use Intervention\Image\Tests\GdTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\GreyscaleModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\GreyscaleModifier::class)]
final class GreyscaleModifierTest extends GdTestCase
{
    public function testColorChange(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertFalse($image->pickColor(0, 0)->isGreyscale());
        $image->modify(new GreyscaleModifier());
        $this->assertTrue($image->pickColor(0, 0)->isGreyscale());
    }
}
