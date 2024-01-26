<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Maps;

use Haszi\DataTypes\CompositeTypes\Collection;
use \IteratorAggregate;
use \Traversable;
use \OutOfBoundsException;

use function \array_search;
use function \array_splice;
use function \count;
use function \in_array;

/**
 * A collection of key-value pairs where each key is unique
 * and is associated with only one value
 */
class Map implements Collection, IteratorAggregate
{
    /** @var array<int, mixed> */
    private array $keys = [];

    /** @var array<int, mixed> */
    private array $values = [];

    public function count(): int
    {
        return count($this->keys);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function clear(): void
    {
        $this->keys = $this->values = [];
    }

    public function getIterator(): Traversable
    {
        for ($i = 0; $i < count($this->keys); ++$i) {
            yield $this->keys[$i] => $this->values[$i];
        }
    }

    public function containsKey(mixed $key): bool
    {
        return in_array($key, $this->keys, true);
    }

    public function containsValue(mixed $value): bool
    {
        return in_array($value, $this->values, true);
    }

    public function insert(mixed $key, mixed $value): void
    {
        if ($this->containsKey($key)) {
            $this->values[$this->getKeysOffset($key)] = $value;

            return;
        }

        $this->keys[] = $key;
        $this->values[] = $value;
    }

    private function getKeysOffset(mixed $key): int
    {
        $offset = array_search($key, $this->keys, true);

        if ($offset === false) {
            throw new OutOfBoundsException();
        }

        $offset = (int) $offset;

        return $offset;
    }

    public function get(mixed $key): mixed
    {
        $offset = $this->getKeysOffset($key);

        return $this->values[$offset];
    }

    public function remove(mixed $key): void
    {
        $offset = $this->getKeysOffset($key);

        array_splice($this->keys, $offset, 1);
        array_splice($this->values, $offset, 1);
    }
}
