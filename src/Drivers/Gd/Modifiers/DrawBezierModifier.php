<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\Geometry_Exception;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
use Intervention\Image\Modifiers\Draw_Bezier_Modifier as GenericDrawBezierModifier;
use RuntimeException;
class Draw_Bezier_Modifier extends Generic_Draw_Bezier_Modifier implements Specialized_Interface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws RuntimeException
     * @throws GeometryException
     */
    public function apply(Image_Interface $image): Image_Interface
    {
        foreach ($image as $frame) {
            if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
                throw new Geometry_Exception('You must specify either 3 or 4 points to create a bezier curve');
            }
            [$polygon, $polygon_border_segments] = $this->calculate_bezier_points();
            if ($this->drawable->has_background_color() || $this->drawable->has_border()) {
                imagealphablending($frame->native(), true);
                imageantialias($frame->native(), true);
            }
            if ($this->drawable->has_background_color()) {
                $background_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->background_color());
                imagesetthickness($frame->native(), 0);
                imagefilledpolygon($frame->native(), $polygon, $background_color);
            }
            if ($this->drawable->has_border() && $this->drawable->border_size() > 0) {
                $border_color = $this->driver()->color_processor($image->colorspace())->color_to_native($this->border_color());
                if ($this->drawable->border_size() === 1) {
                    imagesetthickness($frame->native(), $this->drawable->border_size());
                    $count = count($polygon);
                    for ($i = 0; $i < $count; $i += 2) {
                        if (array_key_exists($i + 2, $polygon) && array_key_exists($i + 3, $polygon)) {
                            imageline($frame->native(), $polygon[$i], $polygon[$i + 1], $polygon[$i + 2], $polygon[$i + 3], $border_color);
                        }
                    }
                } else {
                    $polygon_border_segments_total = count($polygon_border_segments);
                    for ($i = 0; $i < $polygon_border_segments_total; $i += 1) {
                        imagefilledpolygon($frame->native(), $polygon_border_segments[$i], $border_color);
                    }
                }
            }
        }
        return $image;
    }
    /**
     * Calculate interpolation points for quadratic beziers using the Bernstein polynomial form
     *
     * @return array{'x': float, 'y': float}
     */
    private function calculate_quadratic_bezier_interpolation_point(float $t = 0.05): array
    {
        $remainder = 1 - $t;
        $control_point_1_multiplier = $remainder * $remainder;
        $control_point_2_multiplier = $remainder * $t * 2;
        $control_point_3_multiplier = $t * $t;
        $x = $this->drawable->first()->x() * $control_point_1_multiplier + $this->drawable->second()->x() * $control_point_2_multiplier + $this->drawable->last()->x() * $control_point_3_multiplier;
        $y = $this->drawable->first()->y() * $control_point_1_multiplier + $this->drawable->second()->y() * $control_point_2_multiplier + $this->drawable->last()->y() * $control_point_3_multiplier;
        return ['x' => $x, 'y' => $y];
    }
    /**
     * Calculate interpolation points for cubic beziers using the Bernstein polynomial form
     *
     * @return array{'x': float, 'y': float}
     */
    private function calculate_cubic_bezier_interpolation_point(float $t = 0.05): array
    {
        $remainder = 1 - $t;
        $t_squared = $t * $t;
        $remainder_squared = $remainder * $remainder;
        $control_point_1_multiplier = $remainder_squared * $remainder;
        $control_point_2_multiplier = $remainder_squared * $t * 3;
        $control_point_3_multiplier = $t_squared * $remainder * 3;
        $control_point_4_multiplier = $t_squared * $t;
        $x = $this->drawable->first()->x() * $control_point_1_multiplier + $this->drawable->second()->x() * $control_point_2_multiplier + $this->drawable->third()->x() * $control_point_3_multiplier + $this->drawable->last()->x() * $control_point_4_multiplier;
        $y = $this->drawable->first()->y() * $control_point_1_multiplier + $this->drawable->second()->y() * $control_point_2_multiplier + $this->drawable->third()->y() * $control_point_3_multiplier + $this->drawable->last()->y() * $control_point_4_multiplier;
        return ['x' => $x, 'y' => $y];
    }
    /**
     * Calculate the points needed to draw a quadratic or cubic bezier with optional border/stroke
     *
     * @throws GeometryException
     * @return array{0: array<mixed>, 1: array<mixed>}
     */
    private function calculate_bezier_points(): array
    {
        if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
            throw new Geometry_Exception('You must specify either 3 or 4 points to create a bezier curve');
        }
        $polygon = [];
        $inner_polygon = [];
        $outer_polygon = [];
        $polygon_border_segments = [];
        // define ratio t; equivalent to 5 percent distance along edge
        $t = 0.05;
        $polygon[] = $this->drawable->first()->x();
        $polygon[] = $this->drawable->first()->y();
        for ($i = $t; $i < 1; $i += $t) {
            if ($this->drawable->count() === 3) {
                $ip = $this->calculate_quadratic_bezier_interpolation_point($i);
            } elseif ($this->drawable->count() === 4) {
                $ip = $this->calculate_cubic_bezier_interpolation_point($i);
            }
            $polygon[] = (int) $ip['x'];
            $polygon[] = (int) $ip['y'];
        }
        $polygon[] = $this->drawable->last()->x();
        $polygon[] = $this->drawable->last()->y();
        if ($this->drawable->has_border() && $this->drawable->border_size() > 1) {
            // create the border/stroke effect by calculating two new curves with offset positions
            // from the main polygon and then connecting the inner/outer curves to create separate
            // 4-point polygon segments
            $polygon_total_points = count($polygon);
            $offset = $this->drawable->border_size() / 2;
            for ($i = 0; $i < $polygon_total_points; $i += 2) {
                if (array_key_exists($i + 2, $polygon) && array_key_exists($i + 3, $polygon)) {
                    $dx = $polygon[$i + 2] - $polygon[$i];
                    $dy = $polygon[$i + 3] - $polygon[$i + 1];
                    $dxy_sqrt = ($dx * $dx + $dy * $dy) ** 0.5;
                    // inner polygon
                    $scale = $offset / $dxy_sqrt;
                    $ox = -$dy * $scale;
                    $oy = $dx * $scale;
                    $inner_polygon[] = $ox + $polygon[$i];
                    $inner_polygon[] = $oy + $polygon[$i + 1];
                    $inner_polygon[] = $ox + $polygon[$i + 2];
                    $inner_polygon[] = $oy + $polygon[$i + 3];
                    // outer polygon
                    $scale = -$offset / $dxy_sqrt;
                    $ox = -$dy * $scale;
                    $oy = $dx * $scale;
                    $outer_polygon[] = $ox + $polygon[$i];
                    $outer_polygon[] = $oy + $polygon[$i + 1];
                    $outer_polygon[] = $ox + $polygon[$i + 2];
                    $outer_polygon[] = $oy + $polygon[$i + 3];
                }
            }
            $inner_polygon_total_points = count($inner_polygon);
            for ($i = 0; $i < $inner_polygon_total_points; $i += 2) {
                if (array_key_exists($i + 2, $inner_polygon) && array_key_exists($i + 3, $inner_polygon)) {
                    $polygon_border_segments[] = [$inner_polygon[$i], $inner_polygon[$i + 1], $outer_polygon[$i], $outer_polygon[$i + 1], $outer_polygon[$i + 2], $outer_polygon[$i + 3], $inner_polygon[$i + 2], $inner_polygon[$i + 3]];
                }
            }
        }
        return [$polygon, $polygon_border_segments];
    }
}