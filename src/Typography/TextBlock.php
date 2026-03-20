<?php

declare (strict_types=1);
namespace Intervention\Image\Typography;

use Intervention\Image\Collection;
class Text_Block extends Collection
{
    /**
     * Create new text block object
     */
    public function __construct(string $text)
    {
        foreach (explode("\n", $text) as $line) {
            $this->push(new Line($line));
        }
    }
    /**
     * Return array of lines in text block
     *
     * @return array<Line>
     */
    public function lines(): array
    {
        return $this->items;
    }
    /**
     * Set lines of the text block
     *
     * @param array<Line> $lines
     */
    public function set_lines(array $lines): self
    {
        $this->items = $lines;
        return $this;
    }
    /**
     * Get line by given key
     */
    public function line(mixed $key): ?Line
    {
        if (!array_key_exists($key, $this->lines())) {
            return null;
        }
        return $this->lines()[$key];
    }
    /**
     * Return line with most characters of text block
     */
    public function longest_line(): Line
    {
        $lines = $this->lines();
        usort($lines, fn(Line $a, Line $b): int => $b->length() <=> $a->length());
        return $lines[0];
    }
}