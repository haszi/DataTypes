<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Sequences;

use Haszi\DataTypes\CompositeTypes\Collection;
use \UnderflowException;

use function \array_merge;
use function \array_pop;
use function \count;
use function \is_array;

/**
 * An ordered sequence of elements where elements can only be added at one end
 * and removed from the other (FIFO - First In, First Out),
 * and each element can occur more than once
 */
class Queue implements Collection
{
    /** @var array<mixed, mixed> */
    private array $array = [];

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

    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        return $this->array[$this->count() - 1];
    }

    public function enqueue(mixed $value): void
    {
        if (! is_array($value)) {
            $value = [$value];
        }

        $this->array = array_merge($value, $this->array);
    }

    public function dequeue(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        return array_pop($this->array);
    }
}
