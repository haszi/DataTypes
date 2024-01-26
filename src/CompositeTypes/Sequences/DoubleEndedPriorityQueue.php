<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Sequences;

use Haszi\DataTypes\CompositeTypes\Collection;
use \UnderflowException;

use function \array_key_first;
use function \array_key_last;
use function \array_merge;
use function \array_pop;
use function \array_reduce;
use function \ksort;
use function \count;
use function \is_array;

/**
 * An ordered sequence of elements (each associated with a priority)
 * where each element can occur more than once
 * and elements with the highest or lower priority can be returned
 */
class DoubleEndedPriorityQueue implements Collection
{
    /** @var array<int, array<mixed>> */
    private array $array = [];

    public function count(): int
    {
        return (int) array_reduce(
            $this->array,
            function ($sum, $samePriorityItems) {
                return $sum += count($samePriorityItems);
            },
            0
        );
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function clear(): void
    {
        $this->array = [];
    }

    public function peekMax(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        $highestPriority = array_key_last($this->array);
        $numOfHighestPriorityItems = count($this->array[$highestPriority]);

        return $this->array[$highestPriority][$numOfHighestPriorityItems - 1];
    }

    public function peekMin(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        $lowestPriority = array_key_first($this->array);
        $numOfLowestPriorityItems = count($this->array[$lowestPriority]);

        return $this->array[$lowestPriority][$numOfLowestPriorityItems - 1];
    }

    public function enqueue(mixed $value, int $priority): void
    {
        if (!isset($this->array[$priority])) {
            $this->array[$priority] = [$value];

            ksort($this->array);

            return;
        }

        if (! is_array($value)) {
            $value = [$value];
        }

        $this->array[$priority] = array_merge($value, $this->array[$priority]);
    }

    public function dequeueMax(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        $highestPriority = array_key_last($this->array);

        $element = array_pop($this->array[$highestPriority]);

        if (count($this->array[$highestPriority]) === 0) {
            unset($this->array[$highestPriority]);
        }

        return $element;
    }

    public function dequeueMin(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        $lowestPriority = array_key_first($this->array);

        $element = array_pop($this->array[$lowestPriority]);

        if (count($this->array[$lowestPriority]) === 0) {
            unset($this->array[$lowestPriority]);
        }

        return $element;
    }
}
