<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Sequences;

use Haszi\DataTypes\CompositeTypes\Collection;
use \UnderflowException;

use function \array_pop;
use function \count;

/**
 * An ordered sequence of elements where elements can only be added to and removed from
 * the same end (LIFO - Last In, First Out), and each element can occur more than once
 */
class Stack implements Collection
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

    public function push(mixed $value): void
    {
        $this->array[] = $value;
    }

    public function pop(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        return array_pop($this->array);
    }
}
