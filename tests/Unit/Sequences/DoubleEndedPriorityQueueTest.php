<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sequences\DoubleEndedPriorityQueue;
use PHPUnit\Framework\TestCase;
use UnderflowException;

final class DoubleEndedPriorityQueueTest extends TestCase
{
    private ?DoubleEndedPriorityQueue $doubleEndedPriorityQueue;

    public function setUp(): void
    {
        $this->doubleEndedPriorityQueue = new DoubleEndedPriorityQueue();
    }

    public function testQueueGetsCreated()
    {
        $this->assertInstanceOf(DoubleEndedPriorityQueue::class, $this->doubleEndedPriorityQueue);
    }

    public function testIsEmptyIsCorrect()
    {
        $this->assertTrue($this->doubleEndedPriorityQueue->isEmpty());

        $this->doubleEndedPriorityQueue->enqueue(123, 1);

        $this->assertFalse($this->doubleEndedPriorityQueue->isEmpty());
    }

    public function testEmptyCountIsZero()
    {
        $this->assertSame(0, $this->doubleEndedPriorityQueue->count());
    }

    public function testCountIsCorrect()
    {
        $this->assertSame(0, $this->doubleEndedPriorityQueue->count());

        $this->doubleEndedPriorityQueue->enqueue(123, 1);
        $this->doubleEndedPriorityQueue->enqueue(456, 1);
        $this->doubleEndedPriorityQueue->enqueue(789, 3);

        $this->assertSame(3, $this->doubleEndedPriorityQueue->count());

        $this->doubleEndedPriorityQueue->dequeueMax();

        $this->assertSame(2, $this->doubleEndedPriorityQueue->count());

        $this->doubleEndedPriorityQueue->dequeueMin();

        $this->assertSame(1, $this->doubleEndedPriorityQueue->count());
    }

    public function testPeekMaxReturnsCorrectValue()
    {
        $this->doubleEndedPriorityQueue->enqueue(123, 1);
        $this->doubleEndedPriorityQueue->enqueue(456, 1);
        $this->doubleEndedPriorityQueue->enqueue('xyz', 9);
        $this->doubleEndedPriorityQueue->enqueue('abc', 9);
        $this->doubleEndedPriorityQueue->enqueue('def', 4);

        $this->assertSame('xyz', $this->doubleEndedPriorityQueue->peekMax());
    }

    public function testPeekMinReturnsCorrectValue()
    {
        $this->doubleEndedPriorityQueue->enqueue(123, 1);
        $this->doubleEndedPriorityQueue->enqueue(456, 1);
        $this->doubleEndedPriorityQueue->enqueue('xyz', 9);
        $this->doubleEndedPriorityQueue->enqueue('abc', 9);
        $this->doubleEndedPriorityQueue->enqueue('def', 4);

        $this->assertSame(123, $this->doubleEndedPriorityQueue->peekMin());
    }

    public function testPeekMaxThrowsOnEmptyQueue()
    {
        $this->expectException(UnderflowException::class);

        $this->doubleEndedPriorityQueue->peekMax();
    }

    public function testPeekMinThrowsOnEmptyQueue()
    {
        $this->expectException(UnderflowException::class);

        $this->doubleEndedPriorityQueue->peekMin();
    }

    public function testDequeueMaxPopsCorrectValue()
    {
        $this->doubleEndedPriorityQueue->enqueue(123, 3);
        $this->doubleEndedPriorityQueue->enqueue(1111, 2);
        $this->doubleEndedPriorityQueue->enqueue(1244, 3);
        $this->doubleEndedPriorityQueue->enqueue(456, 6);
        $this->doubleEndedPriorityQueue->enqueue(777, 5);
        $this->doubleEndedPriorityQueue->enqueue(555, 5);
        $this->doubleEndedPriorityQueue->enqueue(999, 5);

        $this->assertSame(456, $this->doubleEndedPriorityQueue->dequeueMax());
        $this->assertSame(777, $this->doubleEndedPriorityQueue->dequeueMax());
        $this->assertSame(555, $this->doubleEndedPriorityQueue->dequeueMax());
        $this->assertSame(999, $this->doubleEndedPriorityQueue->dequeueMax());
        $this->assertSame(123, $this->doubleEndedPriorityQueue->dequeueMax());
        $this->assertSame(1244, $this->doubleEndedPriorityQueue->dequeueMax());
        $this->assertSame(1111, $this->doubleEndedPriorityQueue->dequeueMax());
    }

    public function testDequeueMinPopCorrectValues()
    {
        $this->doubleEndedPriorityQueue->enqueue(123, 3);
        $this->doubleEndedPriorityQueue->enqueue(1111, 2);
        $this->doubleEndedPriorityQueue->enqueue(1244, 3);
        $this->doubleEndedPriorityQueue->enqueue(456, 6);
        $this->doubleEndedPriorityQueue->enqueue(777, 5);
        $this->doubleEndedPriorityQueue->enqueue(555, 5);
        $this->doubleEndedPriorityQueue->enqueue(999, 5);

        $this->assertSame(1111, $this->doubleEndedPriorityQueue->dequeueMin());
        $this->assertSame(123, $this->doubleEndedPriorityQueue->dequeueMin());
        $this->assertSame(1244, $this->doubleEndedPriorityQueue->dequeueMin());
        $this->assertSame(777, $this->doubleEndedPriorityQueue->dequeueMin());
        $this->assertSame(555, $this->doubleEndedPriorityQueue->dequeueMin());
        $this->assertSame(999, $this->doubleEndedPriorityQueue->dequeueMin());
        $this->assertSame(456, $this->doubleEndedPriorityQueue->dequeueMin());
    }

    public function testDequeueMaxThrowsOnEmptyQueue()
    {
        $this->expectException(UnderflowException::class);

        $this->doubleEndedPriorityQueue->dequeueMax();
    }

    public function testDequeueMinThrowsOnEmptyQueue()
    {
        $this->expectException(UnderflowException::class);

        $this->doubleEndedPriorityQueue->dequeueMin();
    }

    public function testClearEmptiesQueue()
    {
        $this->doubleEndedPriorityQueue->enqueue(123, 3);
        $this->doubleEndedPriorityQueue->enqueue(456, 3);

        $this->assertSame(2, $this->doubleEndedPriorityQueue->count());

        $this->doubleEndedPriorityQueue->clear();

        $this->assertTrue($this->doubleEndedPriorityQueue->isEmpty());
    }
}
