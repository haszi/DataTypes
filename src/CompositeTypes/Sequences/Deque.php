<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Sequences;

use Haszi\DataTypes\CompositeTypes\Collection;
use \UnderflowException;

use function \array_merge;
use function \array_pop;
use function \array_shift;
use function \count;
use function \is_array;

/**
 * An ordered sequence of elements where elements can only be added to and removed from
 * either the front or the back, and each element can occur more than once
 */
class Deque implements Collection
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

    public function last(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        return $this->array[$this->count() - 1];
    }

    public function first(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        return $this->array[0];
    }

    public function unshift(mixed $value): void
    {
        if (! is_array($value)) {
            $value = [$value];
        }

        $this->array = array_merge($value, $this->array);
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

    public function shift(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        return array_shift($this->array);
    }
}
