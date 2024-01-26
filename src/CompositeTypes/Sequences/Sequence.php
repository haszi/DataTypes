<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Sequences;

use Haszi\DataTypes\CompositeTypes\Collection;
use \IteratorAggregate;
use \Traversable;
use \UnderflowException;

use function \array_merge;
use function \array_pop;
use function \array_search;
use function \array_shift;
use function \array_splice;
use function \count;
use function \in_array;

/**
 * An ordered sequence of elements where each element can occur more than once
 */
class Sequence implements Collection, IteratorAggregate
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

    public function first(): mixed
    {
        return $this->get(0);
    }

    public function last(): mixed
    {
        return $this->get($this->count() - 1);
    }

    public function get(int $index): mixed
    {
        if (! $this->isIndexValid($index)) {
            throw new UnderflowException();
        }

        return $this->array[$index];
    }

    public function isIndexValid(int $index): bool
    {
        return !($index < 0 || $index > $this->count() - 1);
    }

    public function set(int $index, mixed $value): void
    {
        if (! $this->isIndexValid($index)) {
            throw new UnderflowException();
        }

        $this->array[$index] = $value;
    }

    public function insert(int $index, mixed $value): void
    {
        if ($index === $this->count()) {

            $this->push($value);

            return;
        }

        if (! $this->isIndexValid($index)) {
            throw new UnderflowException();
        }

        array_splice($this->array, $index, 0, $value);
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

    public function unshift(mixed $value): void
    {
        if (! is_array($value)) {
            $value = [$value];
        }

        $this->array = array_merge($value, $this->array);
    }

    public function remove(int $index): void
    {
        if (! $this->isIndexValid($index)) {
            throw new UnderflowException();
        }

        array_splice($this->array, $index, 1);
    }

    public function contains(mixed $value): bool
    {
        return in_array($value, $this->array, true);
    }

    public function find(mixed $value):  ?int
    {
        $index = array_search($value, $this->array, true);

        return $index === false ? null : (int) $index;
    }

    public function getIterator(): Traversable
    {
        yield from $this->array;
    }
}
