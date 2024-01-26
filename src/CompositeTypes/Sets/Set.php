<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Sets;

use Haszi\DataTypes\CompositeTypes\Collection;
use \IteratorAggregate;
use \Traversable;

use function \array_search;
use function \array_splice;
use function \count;
use function \in_array;
use function \is_iterable;

/**
 * An unordered collection of unique elements
 */
class Set implements Collection, IteratorAggregate
{
    /** @var array<int, mixed> */
    private array $array = [];

    /**
     * @param iterable<mixed> $values
     */
    public function __construct(iterable $values = [])
    {
        foreach($values as $value) {
            $this->add($value);
        }
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function clear(): void
    {
        $this->array = [];
    }

    public function contains(mixed ...$values): bool
    {
        foreach ($values as $value) {

            if (is_iterable($value)) {
                foreach ($value as $innerValue) {
                    if ($this->containsValue($innerValue) === false) {
                        return false;
                    }
                }

                continue;
            }

            if ($this->containsValue($value) === false) {
                return false;
            }
        }

        return true;
    }

    private function containsValue(mixed $value): bool
    {
        if (!in_array($value, $this->array, true)) {
            return false;
        }

        return true;
    }

    public function add(mixed ...$values): void
    {
        foreach ($values as $value) {

            if (is_iterable($value)) {

                foreach ($value as $innerValue) {
                    if ($this->contains($innerValue)) {
                        continue;
                    }

                    $this->array[] = $value;
                }

                continue;
            }

            if ($this->contains($value)) {
                continue;
            }

            $this->array[] = $value;
        }
    }

    public function remove(mixed ...$values): void
    {
        foreach ($values as $value) {

            if (is_iterable($value)) {

                foreach ($value as $innerValue) {
                    $index = array_search($innerValue, $this->array, true);

                    if ($index === false) {
                        continue;
                    }

                    array_splice($this->array, $index, 1);
                }

                continue;
            }

            $index = array_search($value, $this->array, true);

            if ($index === false) {
                continue;
            }

            array_splice($this->array, $index, 1);
        }
    }

    public function getIterator(): Traversable
    {
        yield from $this->array;
    }
}
