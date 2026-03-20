<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Encoders\AvifEncoder;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(AvifEncoder::class)]
final class AvifEncoderTest extends ImagickTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new AvifEncoder(10);
        $encoder->setDriver(new Driver());
        $result = $encoder->encode($image);
        $this->assertMediaType('image/avif', $result);
        $this->assertEquals('image/avif', $result->mimetype());
    }
}
