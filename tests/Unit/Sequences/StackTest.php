<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sequences\Stack;
use PHPUnit\Framework\TestCase;
use UnderflowException;

final class StackTest extends TestCase
{
    private ?Stack $stack;

    public function setUp(): void
    {
        $this->stack = new Stack();
    }

    public function testStackGetsCreated()
    {
        $this->assertInstanceOf(Stack::class, $this->stack);
    }

    public function testIsEmptyIsCorrect()
    {
        $this->assertTrue($this->stack->isEmpty());

        $this->stack->push(123);

        $this->assertFalse($this->stack->isEmpty());
    }

    public function testEmptyCountIsZero()
    {
        $this->assertSame(0, $this->stack->count());
    }

    public function testCountIsCorrect()
    {
        $this->assertSame(0, $this->stack->count());

        $this->stack->push(123);
        $this->stack->push(456);
        $this->stack->push(789);

        $this->assertSame(3, $this->stack->count());

        $this->stack->pop();

        $this->assertSame(2, $this->stack->count());
    }

    public function testPeekReturnsCorrectValue()
    {
        $this->stack->push('abc');
        $this->stack->push('def');
        $this->stack->push('Peek');

        $this->assertSame('Peek', $this->stack->peek());
    }

    public function testPeekThrowsOnEmptyStack()
    {
        $this->expectException(UnderflowException::class);

        $this->stack->peek();
    }

    public function testPopsCorrectValue()
    {
        $this->stack->push(123);
        $this->stack->push(456);
        $this->stack->push(789);

        $this->assertSame(789, $this->stack->pop());

        $this->assertSame(2, $this->stack->count());
    }

    public function testPopThrowsOnEmptyStack()
    {
        $this->expectException(UnderflowException::class);

        $this->stack->pop();
    }

    public function testClearEmptiesStack()
    {
        $this->stack->push(123);
        $this->stack->push(456);

        $this->assertSame(2, $this->stack->count());

        $this->stack->clear();

        $this->assertSame(0, $this->stack->count());
    }
}
