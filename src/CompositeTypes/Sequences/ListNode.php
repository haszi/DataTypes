<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Sequences;

/**
 * A node used in linked lists
 */
class ListNode
{
    private mixed $value = null;

    private ?ListNode $nextNode = null;

    public function __construct(mixed $value = null, ?ListNode $nextNode = null)
    {
        $this->value = $value;

        $this->nextNode = $nextNode;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setNextNode(?ListNode $nextNode): void
    {
        $this->nextNode = $nextNode;
    }

    public function getNextNode(): ?ListNode
    {
        return $this->nextNode;
    }
}
