<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sequences\PriorityQueue;
use PHPUnit\Framework\TestCase;
use UnderflowException;

final class PriorityQueueTest extends TestCase
{
    private ?PriorityQueue $priorityQueue;

    public function setUp(): void
    {
        $this->priorityQueue = new PriorityQueue();
    }

    public function testQueueGetsCreated()
    {
        $this->assertInstanceOf(PriorityQueue::class, $this->priorityQueue);
    }

    public function testIsEmptyIsCorrect()
    {
        $this->assertTrue($this->priorityQueue->isEmpty());

        $this->priorityQueue->enqueue(123, 1);

        $this->assertFalse($this->priorityQueue->isEmpty());
    }

    public function testEmptyCountIsZero()
    {
        $this->assertSame(0, $this->priorityQueue->count());
    }

    public function testCountIsCorrect()
    {
        $this->assertSame(0, $this->priorityQueue->count());

        $this->priorityQueue->enqueue(123, 1);
        $this->priorityQueue->enqueue(456, 1);
        $this->priorityQueue->enqueue(789, 3);

        $this->assertSame(3, $this->priorityQueue->count());

        $this->priorityQueue->dequeue();

        $this->assertSame(2, $this->priorityQueue->count());
    }

    public function testPeekReturnsCorrectValue()
    {
        $this->priorityQueue->enqueue('Peek', 1);
        $this->priorityQueue->enqueue('xyz', 9);
        $this->priorityQueue->enqueue('abc', 9);
        $this->priorityQueue->enqueue('def', 4);

        $this->assertSame('xyz', $this->priorityQueue->peek());
    }

    public function testPeekThrowsOnEmptyQueue()
    {
        $this->expectException(UnderflowException::class);

        $this->priorityQueue->peek();
    }

    public function testDequeuePopsCorrectValue()
    {
        $this->priorityQueue->enqueue(123, 3);
        $this->priorityQueue->enqueue(456, 6);
        $this->priorityQueue->enqueue(777, 5);
        $this->priorityQueue->enqueue(555, 5);
        $this->priorityQueue->enqueue(999, 5);

        $this->assertSame(456, $this->priorityQueue->dequeue());
        $this->assertSame(777, $this->priorityQueue->dequeue());
        $this->assertSame(555, $this->priorityQueue->dequeue());
        $this->assertSame(999, $this->priorityQueue->dequeue());
        $this->assertSame(123, $this->priorityQueue->dequeue());
    }

    public function testDequeueThrowsOnEmptyQueue()
    {
        $this->expectException(UnderflowException::class);

        $this->priorityQueue->dequeue();
    }

    public function testClearEmptiesQueue()
    {
        $this->priorityQueue->enqueue(123, 3);
        $this->priorityQueue->enqueue(456, 3);

        $this->assertSame(2, $this->priorityQueue->count());

        $this->priorityQueue->clear();

        $this->assertSame(0, $this->priorityQueue->count());
    }
}
