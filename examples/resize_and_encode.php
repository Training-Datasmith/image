<?php

declare(strict_types=1);

/**
 * Example: basic image manipulation with Intervention Image v3 (GD driver).
 *
 * Run from the image project root:
 *   php examples/resize_and_encode.php
 *
 * Requires the GD extension (standard in most PHP builds).
 */

require __DIR__ . '/../vendor/autoload.php';

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

$manager = new ImageManager(new Driver());

// --- Create a blank canvas and draw on it ---
$image = $manager->create(400, 300)
    ->fill('336699')
    ->text('Hello, Image!', 200, 150, function ($font) {
        $font->color('ffffff');
        $font->align('center');
        $font->valign('middle');
        $font->size(32);
    });

echo "Width:  " . $image->width() . "px\n";
echo "Height: " . $image->height() . "px\n";

// --- Resize to thumbnail ---
$thumb = $manager->create(400, 300)
    ->fill('993366')
    ->scale(width: 100);

echo "Thumb width: " . $thumb->width() . "px\n";

// --- Encode to JPEG and inspect byte length ---
$jpeg = $image->toJpeg(quality: 85);
echo "JPEG size: " . strlen((string) $jpeg) . " bytes\n";

// --- Encode to PNG ---
$png = $image->toPng();
echo "PNG size:  " . strlen((string) $png) . " bytes\n";

// --- Encode to WebP ---
$webp = $image->toWebp(quality: 80);
echo "WebP size: " . strlen((string) $webp) . " bytes\n";

// --- Read pixel color ---
$color = $image->pickColor(200, 150);
echo "Pixel at center: " . $color->toHex() . "\n";
