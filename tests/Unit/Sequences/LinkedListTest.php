<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sequences\LinkedList;
use Haszi\DataTypes\CompositeTypes\Sequences\ListNode;
use PHPUnit\Framework\TestCase;

final class LinkedListTest extends TestCase
{
    private ?LinkedList $linkedList;

    public function setUp(): void
    {
        $this->linkedList = new LinkedList();
    }

    public function testDequeGetsCreated()
    {
        $this->assertInstanceOf(LinkedList::class, $this->linkedList);
    }

    public function testIsEmptyIsCorrect()
    {
        $this->assertTrue($this->linkedList->isEmpty());
    }

    public function testEmptyCountIsZero()
    {
        $this->assertSame(0, $this->linkedList->count());
    }

    public function testLinkedListCanBeIteratedOver()
    {
        $this->assertIsIterable($this->linkedList);
    }

    public function testAppendAddsNodeToEndOfList()
    {
        $this->linkedList->append('firstValue');
        $this->linkedList->append('someValue');
        $this->linkedList->append('lastValue');

        $lastNode = null;
        foreach ($this->linkedList as $node) {
            if ($node !== null) {
                $lastNode = $node;
            }
        }

        $this->assertSame('lastValue', $lastNode->getValue());
    }

    public function testPrependAddsNodeToBeginningOfList()
    {
        $this->linkedList->prepend('lastValue');
        $this->linkedList->prepend('someValue');
        $this->linkedList->prepend('firstValue');

        $firstNode = null;
        foreach ($this->linkedList as $node) {
            if ($node !== null) {
                $firstNode = $node;
                break;
            }
        }

        $this->assertSame('firstValue', $firstNode->getValue());
    }

    public function testInsertInsertNodeInTheCorrectPosition()
    {
        $this->linkedList->insert(0, 'lastValue');
        $this->linkedList->insert(0, 'firstValue');
        $this->linkedList->insert(1, 'someValue');

        $position = 1;
        $counter = 0;
        $returnedNode = null;
        foreach ($this->linkedList as $node) {
            if ($position === $counter) {
                $returnedNode = $node;
                break;
            }
            ++$counter;
        }

        $this->assertSame('someValue', $returnedNode->getValue());
    }

    public function testContainsIsCorrect()
    {
        $this->linkedList->append(456);
        $this->linkedList->append(789);
        $this->linkedList->append(135);
        $this->linkedList->append(246);

        $this->assertFalse($this->linkedList->contains(123));

        $this->linkedList->append(123);

        $this->assertTrue($this->linkedList->contains(123));
    }

    public function testPopRemovesLastNode()
    {
        $this->linkedList->append(456);
        $this->linkedList->append(789);
        $this->linkedList->append(135);
        $this->linkedList->append(246);

        $this->assertTrue($this->linkedList->contains(246));

        $this->linkedList->pop();

        $this->assertFalse($this->linkedList->contains(246));
    }

    public function testShiftRemovesFirstNode()
    {
        $this->linkedList->append(456);
        $this->linkedList->append(789);
        $this->linkedList->append(135);
        $this->linkedList->append(246);

        $this->assertTrue($this->linkedList->contains(456));

        $this->linkedList->shift();

        $this->assertFalse($this->linkedList->contains(456));
    }

    public function testRemoveRemovesCorrectNode()
    {
        $this->linkedList->append(456);
        $this->linkedList->append(789);
        $this->linkedList->append(135);
        $this->linkedList->append(246);

        $this->assertTrue($this->linkedList->contains(135));

        $this->linkedList->remove(2);

        $this->assertFalse($this->linkedList->contains(135));
    }
}
