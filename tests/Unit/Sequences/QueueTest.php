<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sequences\Queue;
use PHPUnit\Framework\TestCase;
use UnderflowException;

final class QueueTest extends TestCase
{
    private ?Queue $queue;

    public function setUp(): void
    {
        $this->queue = new Queue();
    }

    public function testQueueGetsCreated()
    {
        $this->assertInstanceOf(Queue::class, $this->queue);
    }

    public function testIsEmptyIsCorrect()
    {
        $this->assertTrue($this->queue->isEmpty());

        $this->queue->enqueue(123);

        $this->assertFalse($this->queue->isEmpty());
    }

    public function testEmptyCountIsZero()
    {
        $this->assertSame(0, $this->queue->count());
    }

    public function testCountIsCorrect()
    {
        $this->assertSame(0, $this->queue->count());

        $this->queue->enqueue(123);
        $this->queue->enqueue(456);
        $this->queue->enqueue(789);

        $this->assertSame(3, $this->queue->count());

        $this->queue->dequeue();

        $this->assertSame(2, $this->queue->count());
    }

    public function testPeekReturnsCorrectValue()
    {
        $this->queue->enqueue('Peek');
        $this->queue->enqueue('abc');
        $this->queue->enqueue('def');

        $this->assertSame('Peek', $this->queue->peek());
    }

    public function testPeekThrowsOnEmptyQueue()
    {
        $this->expectException(UnderflowException::class);

        $this->queue->peek();
    }

    public function testDequeuePopsCorrectValue()
    {
        $this->queue->enqueue(123);
        $this->queue->enqueue(456);
        $this->queue->enqueue(789);

        $this->assertSame(123, $this->queue->dequeue());

        $this->assertSame(2, $this->queue->count());
    }

    public function testDequeueThrowsOnEmptyQueue()
    {
        $this->expectException(UnderflowException::class);

        $this->queue->dequeue();
    }

    public function testClearEmptiesQueue()
    {
        $this->queue->enqueue(123);
        $this->queue->enqueue(456);

        $this->assertSame(2, $this->queue->count());

        $this->queue->clear();

        $this->assertSame(0, $this->queue->count());
    }
}
