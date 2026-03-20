# Architecture: image

## Purpose
Intervention Image v3 — a PHP image manipulation library supporting both GD and Imagick drivers. Provides a fluent API for resizing, cropping, encoding, color analysis, and drawing.

## Directory Structure
```
src/
  ImageManager.php / Manager.php  # Entry point — creates Image instances via configured driver
  Image.php                       # Fluent image object — all manipulation methods
  Drivers/
    Gd/                           # GD2 driver implementation
      Core.php                    # Wraps GD resource
      Encoders/                   # Jpeg, Png, Gif, Webp, Avif, Bmp encoders
      Decoders/                   # Binary, File, Base64, DataUri, SplFileInfo decoders
      Analyzers/                  # Width, Height, Colorspace, Pixel, Resolution analyzers
      Modifiers/                  # All image transformations (resize, crop, rotate, etc.)
    Imagick/                      # Imagick driver — same structure as Gd/
  Colors/
    Rgb/                          # RGB colorspace + channels (R, G, B, Alpha)
    Cmyk/                         # CMYK colorspace + channels
    Hsl/                          # HSL colorspace
    Hsv/                          # HSV colorspace
  Geometry/
    Rectangle.php                 # Axis-aligned rectangle with position helpers
    Ellipse.php / Circle.php      # Ellipse and circle primitives
    Line.php                      # Line segment
    Factories/                    # Fluent builders for geometry objects
  Typography/
    Font.php                      # Font configuration (family, size, color, angle)
    Text_Block.php                # Multi-line text measurement and layout
  Exceptions/
    Runtime_Exception.php
  Encoders/                       # Encoded image value objects
  Interfaces/                     # Contracts for driver, modifier, analyzer, decoder, encoder
src/Colors/Rgb/Channels/          # Individual R/G/B/Alpha channel value objects
tests/
```

## Key Design Decisions
- **Driver abstraction** — all pixel-level operations are delegated to a swappable driver (`Gd` or `Imagick`). Callers use only the `Image` API, never driver internals.
- **Specializable operations** — the `Specializable_Modifier`, `Specializable_Analyzer`, and `Specializable_Decoder` traits route generic `modify()` / `analyze()` calls to the driver-specific implementation.
- **Value objects for colors and geometry** — colors and shapes are immutable value objects, making transformations composable and testable in isolation.
- **Encoded image objects** — encoding produces an `EncodedImage` value object (not a raw string) that carries MIME type information and can be streamed or saved.

## Extension Points
- Implement the `DriverInterface` to add a new image processing backend.
- Implement `ModifierInterface` to add a custom image transformation.
- Implement `AnalyzerInterface` to add a new image analysis operation.
- Register custom encoders/decoders via the driver configuration.

## Dependency Flow
```
ImageManager::read($source)
  └─ Driver\Decoder → Image (wraps Driver\Core)

Image::resize() / crop() / rotate() / ...
  └─ Driver\Modifier::apply(Image) → mutates Core

Image::encode('jpeg', 80)
  └─ Driver\Encoder → EncodedImage (MIME + binary data)
```
