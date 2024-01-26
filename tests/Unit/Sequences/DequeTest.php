<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sequences\Deque;
use PHPUnit\Framework\TestCase;
use UnderflowException;

final class DequeTest extends TestCase
{
    private ?Deque $deque;

    public function setUp(): void
    {
        $this->deque = new Deque();
    }

    public function testDequeGetsCreated()
    {
        $this->assertInstanceOf(Deque::class, $this->deque);
    }

    public function testIsEmptyIsCorrect()
    {
        $this->assertTrue($this->deque->isEmpty());
    }

    public function testEmptyCountIsZero()
    {
        $this->assertSame(0, $this->deque->count());
    }

    public function testPushWorksAndCountIsCorrect()
    {
        $this->assertSame(0, $this->deque->count());

        $this->deque->push(123);

        $this->assertSame(1, $this->deque->count());

        $this->deque->push(456);

        $this->assertSame(2, $this->deque->count());
    }

    public function testUnshiftWorksAndCountIsCorrect()
    {
        $this->assertSame(0, $this->deque->count());

        $this->deque->unshift(123);

        $this->assertSame(1, $this->deque->count());

        $this->deque->unshift(456);

        $this->assertSame(2, $this->deque->count());
    }

    public function testLastReturnsCorrectValue()
    {
        $this->deque->push('abc');
        $this->deque->push('def');
        $this->deque->push('Peek');

        $this->assertSame('Peek', $this->deque->last());
    }

    public function testLastThrowsOnEmptyDeque()
    {
        $this->expectException(UnderflowException::class);

        $this->deque->last();
    }

    public function testFirstReturnsCorrectValue()
    {
        $this->deque->push('Peek');
        $this->deque->push('abc');
        $this->deque->push('def');

        $this->assertSame('Peek', $this->deque->first());
    }

    public function testFirstThrowsOnEmptyDeque()
    {
        $this->expectException(UnderflowException::class);

        $this->deque->first();
    }

    public function testPopPopsCorrectValue()
    {
        $this->deque->push(123);
        $this->deque->push(456);
        $this->deque->push(789);

        $this->assertSame(789, $this->deque->pop());

        $this->assertSame(2, $this->deque->count());
    }

    public function testShiftPopsCorrectValue()
    {
        $this->deque->unshift(123);
        $this->deque->unshift(456);
        $this->deque->unshift(789);

        $this->assertSame(789, $this->deque->shift());

        $this->assertSame(2, $this->deque->count());
    }

    public function testPopThrowsOnEmptyDeque()
    {
        $this->expectException(UnderflowException::class);

        $this->deque->pop();
    }

    public function testShiftThrowsOnEmptyDeque()
    {
        $this->expectException(UnderflowException::class);

        $this->deque->shift();
    }

    public function testClearEmptiesQueue()
    {
        $this->deque->unshift(123);
        $this->deque->unshift(456);

        $this->assertSame(2, $this->deque->count());

        $this->deque->clear();

        $this->assertSame(0, $this->deque->count());
    }
}
