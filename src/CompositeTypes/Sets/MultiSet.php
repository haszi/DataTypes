<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Sets;

use Haszi\DataTypes\CompositeTypes\Collection;
use \IteratorAggregate;
use \Traversable;

use function \array_merge;
use function \array_search;
use function \array_slice;
use function \array_sum;
use function \count;
use function \in_array;
use function \is_iterable;

/**
 * An unordered collection of elements where each element can occur more than once
 */
class MultiSet implements Collection, IteratorAggregate
{
    /** @var array<int, mixed> */
    private array $keys = [];

    /** @var array<int, int> */
    private array $values = [];

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
        return array_sum($this->values);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function clear(): void
    {
        $this->keys = $this->values = [];
    }

    public function contains(mixed ...$values): bool
    {
        foreach ($values as $value) {

            if (is_iterable($value)) {

                $value = (is_object($value) && $value instanceof (self::class)) ? $value->keys : $value;

                foreach ($value as $innerValue) {

                    if (! $this->containsValue($innerValue)) {
                        return false;
                    }
                }

                continue;
            }

            if (! $this->containsValue($value)) {
                return false;
            }
        }

        return true;
    }

    private function containsValue(mixed $value): bool
    {
        return in_array($value, $this->keys, true);
    }

    public function add(mixed ...$values): void
    {
        foreach ($values as $value) {

            if (! is_iterable($value)) {
                $this->addValue($value, 1);

                continue;
            }

            $isValueMultiSet = (is_object($value) && $value instanceof (self::class));

            $normalizedValueIterator = ($isValueMultiSet) ? $value->keys : $value;

            $i = 0;

            foreach ($normalizedValueIterator as $innerValue) {

                $valueCount = ($isValueMultiSet) ? $value->values[$i] : 1;

                $this->addValue($innerValue, $valueCount);

                ++$i;
            }
        }
    }

    private function addValue(mixed $value, int $count = 1): void
    {
        if (! $this->contains($value)) {

            $this->keys[] = $value;
            $this->values[] = $count;
            return;
        }

        $index = array_search($value, $this->keys, true);

        if ($index === false) {
            return;
        }

        $index = (int) $index;

        $this->values[$index] += $count;
    }

    public function remove(mixed ...$values): void
    {
        foreach ($values as $value) {

            if (is_iterable($value)) {

                $value = (is_object($value) && $value instanceof (self::class)) ? $value->keys : $value;

                foreach ($value as $innerValue) {

                    $this->removeValue($innerValue);
                }

                continue;
            }

            $this->removeValue($value);
        }
    }

    private function removeValue(mixed $value): void
    {
        if (! $this->contains($value)) {
            return;
        }

        $index = array_search($value, $this->keys, true);

        if ($index === false) {
            return;
        }

        $index = (int) $index;

        --$this->values[$index];

        if ($this->values[$index] > 0) {
            return;
        }

        $this->keys = array_merge(array_slice($this->keys, 0, $index, true), array_slice($this->keys, $index + 1, null, true));

        $this->values = array_merge(array_slice($this->values, 0, $index, true), array_slice($this->values, $index + 1, null, true));
    }

    public function getIterator(): Traversable
    {
        $elementRepetitionCount = 1;

        $keysCount = count($this->keys);
        for ($i = 0; $i < $keysCount; ++$i) {

            yield $this->keys[$i];

            if ($this->values[$i] !== $elementRepetitionCount) {
                ++$elementRepetitionCount;
                --$i;
                continue;
            }

            $elementRepetitionCount = 1;
        }
    }
}
