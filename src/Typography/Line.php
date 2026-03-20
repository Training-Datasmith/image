<?php

declare (strict_types=1);
namespace Intervention\Image\Typography;

use ArrayIterator;
use Countable;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\Point_Interface;
use IteratorAggregate;
use Stringable;
use Traversable;
/**
 * @implements IteratorAggregate<string>
 */
class Line implements IteratorAggregate, Countable, Stringable
{
    /**
     * Segments (usually individual words including punctuation marks) of the line
     *
     * @var array<string>
     */
    protected array $segments = [];
    /**
     * Create new text line object with given text & position
     */
    public function __construct(?string $text = null, protected Point_Interface $position = new Point())
    {
        if (is_string($text)) {
            $this->segments = $this->words_seperated_by_spaces($text) ? explode(' ', $text) : mb_str_split($text);
        }
    }
    /**
     * Add word to current line
     */
    public function add(string $word): self
    {
        $this->segments[] = $word;
        return $this;
    }
    /**
     * Returns Iterator
     *
     * @return Traversable<string>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->segments);
    }
    /**
     * Get Position of line
     */
    public function position(): Point_Interface
    {
        return $this->position;
    }
    /**
     * Set position of current line
     */
    public function set_position(Point_Interface $point): self
    {
        $this->position = $point;
        return $this;
    }
    /**
     * Count segments (individual words including punctuation marks) of line
     */
    public function count(): int
    {
        return count($this->segments);
    }
    /**
     * Count characters of line
     */
    public function length(): int
    {
        return mb_strlen((string) $this);
    }
    /**
     * Dermine if words are sperarated by spaces in the written language of the given text
     */
    private function words_seperated_by_spaces(string $text): bool
    {
        return 1 !== preg_match('/[' . '\x{4E00}-\x{9FFF}' . '\x{3400}-\x{4DBF}' . '\x{3040}-\x{309F}' . '\x{30A0}-\x{30FF}' . '\x{0E00}-\x{0E7F}' . ']/u', $text);
    }
    /**
     * Cast line to string
     */
    public function __toString(): string
    {
        $string = implode('', $this->segments);
        if ($this->words_seperated_by_spaces($string)) {
            return implode(' ', $this->segments);
        }
        return $string;
    }
}