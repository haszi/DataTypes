<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Sequences;

use Haszi\DataTypes\CompositeTypes\Collection;
use Haszi\DataTypes\CompositeTypes\Sequences\ListNode;
use \IteratorAggregate;
use \Traversable;
use \UnderflowException;

/**
 * An ordered sequence of elements where each element points to the next one (singly linked list)
 */
class LinkedList implements Collection, IteratorAggregate
{
    private ?ListNode $firstNode = null;

    private int $count = 0;

    public function count(): int
    {
        return $this->count;
    }

    public function isEmpty(): bool
    {
        return $this->count === 0;
    }

    public function clear(): void
    {
        $this->firstNode = null;
        $this->count = 0;
    }

    public function contains(mixed $value): bool
    {
        $currentNode = $this->firstNode;

        while ($currentNode !== null) {
            if ($currentNode->getValue() === $value) {
                return true;
            }
            $currentNode = $currentNode->getNextNode();
        }

        return false;
    }

    public function append(mixed $value): void
    {
        $this->insert($this->count, $value);
    }

    public function prepend(mixed $value): void
    {
        $this->insert(0, $value);
    }

    public function insert(int $position, mixed $value): void
    {
        if ($position < 0
            || $position > $this->count) {

            throw new UnderflowException();
        }

        $newNode = new ListNode($value);

        if ($this->firstNode === null) {
            $this->firstNode = $newNode;
            ++$this->count;
            return;
        }

        $previousNode = null;
        $currentNode = $this->firstNode;

        for ($i = 0; $i <= $position; ++$i) {

            if ($i === $position) {

                $newNode->setNextNode($currentNode);
                if ($previousNode !== null) {
                    $previousNode->setNextNode($newNode);
                } else {
                    $this->firstNode = $newNode;
                }

                ++$this->count;
                break;
            }

            if ($currentNode === null) {
                break;
            }

            $previousNode = $currentNode;
            $currentNode = $currentNode->getNextNode();
        }
    }

    public function pop(): void
    {
        $this->remove($this->count - 1);
    }

    public function shift(): void
    {
        $this->remove(0);
    }

    public function remove(int $position): void
    {
        if ($this->firstNode === null) {
            return;
        }

        $previousNode = null;
        $currentNode = $this->firstNode;

        for ($i = 0; $i <= $position; ++$i) {
            if ($currentNode === null) {
                break;
            }

            if ($i === $position) {

                if ($previousNode === null) {
                    $this->firstNode = $currentNode->getNextNode();
                } else {
                    $previousNode->setNextNode($currentNode->getNextNode());
                }
                --$this->count;

                break;
            }

            $previousNode = $currentNode;
            $currentNode = $currentNode->getNextNode();
        }
    }

    public function getIterator(): Traversable
    {
        $currentNode = $this->firstNode;

        while ($currentNode !== null) {
            yield $currentNode;

            $currentNode = $currentNode->getNextNode();
        }
    }
}
