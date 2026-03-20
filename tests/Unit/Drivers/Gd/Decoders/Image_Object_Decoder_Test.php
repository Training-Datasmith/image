<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Decoders;

use Intervention\Image\Decoders\ImageObjectDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\GdTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
#[CoversClass(ImageObjectDecoder::class)]
final class ImageObjectDecoderTest extends GdTestCase
{
    public function testDecode(): void
    {
        $decoder = new ImageObjectDecoder();
        $result = $decoder->decode($this->readTestImage('blue.gif'));
        $this->assertInstanceOf(Image::class, $result);
    }
}
