<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Encoders\TiffEncoder;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(TiffEncoder::class)]
final class TiffEncoderTest extends ImagickTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new TiffEncoder();
        $encoder->setDriver(new Driver());
        $result = $encoder->encode($image);
        $this->assertMediaType('image/tiff', $result);
        $this->assertEquals('image/tiff', $result->mimetype());
    }
}
