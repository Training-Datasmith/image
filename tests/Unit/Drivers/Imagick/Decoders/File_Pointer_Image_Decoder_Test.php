<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Decoders\FilePointerImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Image;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(FilePointerImageDecoder::class)]
final class FilePointerImageDecoderTest extends ImagickTestCase
{
    public function testDecode(): void
    {
        $decoder = new FilePointerImageDecoder();
        $decoder->setDriver(new Driver());
        $fp = fopen($this->getTestResourcePath('test.jpg'), 'r');
        $result = $decoder->decode($fp);
        $this->assertInstanceOf(Image::class, $result);
    }
}
