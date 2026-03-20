<?php

declare (strict_types=1);
namespace Intervention\Image\Geometry\Factories;

class Drawable
{
    /**
     * Creeate BezierFactory statically
     */
    public static function bezier(): Bezier_Factory
    {
        return new Bezier_Factory();
    }
    /**
     * Creeate CircleFactory statically
     */
    public static function circle(): Circle_Factory
    {
        return new Circle_Factory();
    }
    /**
     * Create EllipseFactory statically
     */
    public static function ellipse(): Ellipse_Factory
    {
        return new Ellipse_Factory();
    }
    /**
     * Creeate LineFactory statically
     */
    public static function line(): Line_Factory
    {
        return new Line_Factory();
    }
    /**
     * Creeate PolygonFactory statically
     */
    public static function polygon(): Polygon_Factory
    {
        return new Polygon_Factory();
    }
    /**
     * Creeate RectangleFactory statically
     */
    public static function rectangle(): Rectangle_Factory
    {
        return new Rectangle_Factory();
    }
}