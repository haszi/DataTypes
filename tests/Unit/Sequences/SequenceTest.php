<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sequences\Sequence;
use PHPUnit\Framework\TestCase;
use UnderflowException;

final class SequenceTest extends TestCase
{
    private ?Sequence $sequence;

    public function setUp(): void
    {
        $this->sequence = new Sequence();
    }

    public function testSequenceGetsCreated()
    {
        $this->assertInstanceOf(Sequence::class, $this->sequence);
    }

    public function testIsEmptyIsCorrect()
    {
        $this->assertTrue($this->sequence->isEmpty());
    }

    public function testEmptyCountIsZero()
    {
        $this->assertSame(0, $this->sequence->count());
    }

    public function testClearEmptiesSequence()
    {
        $this->sequence->push(123);
        $this->sequence->push(456);

        $this->assertSame(2, $this->sequence->count());

        $this->sequence->clear();

        $this->assertSame(0, $this->sequence->count());
    }

    public function testFirstReturnsFirstElement()
    {
        $this->sequence->push(123);
        $this->sequence->push(456);
        $this->sequence->push(789);

        $this->assertSame(123, $this->sequence->first());
    }

    public function testLastReturnsFirstElement()
    {
        $this->sequence->push(123);
        $this->sequence->push(456);
        $this->sequence->push(789);

        $this->assertSame(789, $this->sequence->last());
    }

    public function testGetReturnsCorrectElement()
    {
        $this->sequence->push(123);
        $this->sequence->push(456);
        $this->sequence->push(789);

        $this->assertSame(456, $this->sequence->get(1));
    }

    /**
     * @dataProvider invalidIndexProvider
     */
    public function testGetThrowsOnInvalidIndex($elements, $invalidIndex)
    {
        foreach ($elements as $element) {
            $this->sequence->push($element);
        }

        $this->expectException(UnderflowException::class);
        $this->sequence->get($invalidIndex);
    }

    public static function invalidIndexProvider()
    {
        return [
            [
                'push' => [
                    123,
                    456,
                    789
                ],
                'invalidIndex' => -1
            ],
            [
                'push' => [
                    123,
                    456,
                    789
                ],
                'invalidIndex' => 3
            ],
        ];
    }

    public function testSetThrowsOnInvalidIndex()
    {
        $this->sequence->push(123);

        $this->expectException(UnderflowException::class);

        $this->sequence->set(1, 456);
    }

    public function testSetChangesValue()
    {
        $this->sequence->push(123);
        $this->sequence->push(456);
        $this->sequence->push(789);

        $this->assertSame(456, $this->sequence->get(1));

        $this->sequence->set(1, 'abc');

        $this->assertSame('abc', $this->sequence->get(1));
    }

    public function testInsertInsertsCorrectValueAndIndex()
    {
        $this->sequence->push(123);
        $this->sequence->push(789);

        $this->sequence->insert(1, 456);

        $this->assertSame(456, $this->sequence->get(1));
    }

    /**
     * @dataProvider invalidInsertIndex
     */
    public function testInsertThrowsOnInvalidIndex($index, $value)
    {
        $this->expectException(UnderflowException::class);

        $this->sequence->insert($index, $value);
    }

    public static function invalidInsertIndex()
    {
        return [
            [
                'index' => -1,
                'value' => 1
            ],
            [
                'index' => 1,
                'value' => 2
            ],
        ];
    }

    public function testPushAddsValue()
    {
        $this->sequence->push(123);

        $this->assertSame(123, $this->sequence->first());
    }

    public function testPopReturnsAndDeletesLastValue()
    {
        $this->sequence->push(123);
        $this->sequence->push(456);

        $this->assertSame(456, $this->sequence->pop());
        $this->assertSame(1, $this->sequence->count());
    }

    public function testPopThrowsOnEmptySequence()
    {
        $this->expectException(UnderflowException::class);

        $this->sequence->pop();
    }

    public function testShiftReturnsAndDeletesFirstValue()
    {
        $this->sequence->push(123);
        $this->sequence->push(456);

        $this->assertSame(123, $this->sequence->shift());
        $this->assertSame(1, $this->sequence->count());
    }

    public function testShiftThrowsOnEmptySequence()
    {
        $this->expectException(UnderflowException::class);

        $this->sequence->shift();
    }

    public function testUnshiftAddsValueToFrontOfSequence()
    {
        $this->sequence->push(123);
        $this->sequence->push(456);
        $this->sequence->unshift(789);

        $this->assertSame(789, $this->sequence->first());
    }

    public function testRemoveDeletesValue()
    {
        $this->sequence->push(123);
        $this->sequence->push(456);
        $this->sequence->push(789);

        $this->sequence->remove(1);

        $this->assertSame(2, $this->sequence->count());
        $this->assertSame(123, $this->sequence->get(0));
        $this->assertSame(789, $this->sequence->get(1));
    }

    /**
     * @dataProvider invalidIndexToRemove
     */
    public function testRemoveThrowsInInvalidIndex($index, $value)
    {
        $this->sequence->push($value);

        $this->expectException(UnderflowException::class);
        $this->sequence->remove($index);
    }

    public static function invalidIndexToRemove()
    {
        return [
            [
                'index' => -1,
                'value' => 1
            ],
            [
                'index' => 1,
                'value' => 2
            ],
        ];
    }

    public function testContainsReturnsCorrectResult()
    {
        $this->sequence->push(123);
        $this->sequence->push('abc');
        $this->sequence->push('456');

        $this->assertTrue($this->sequence->contains(123));
        $this->assertTrue($this->sequence->contains('abc'));
        $this->assertFalse($this->sequence->contains(456));
    }

    public function testFindReturnsCorrectIndex()
    {
        $this->sequence->push(123);
        $this->sequence->push('abc');
        $this->sequence->push('456');

        $this->assertSame(null, $this->sequence->find(456));
        $this->assertSame(2, $this->sequence->find('456'));
        $this->assertSame(0, $this->sequence->find(123));
    }

    public function testSequenceCanBeIteratedOver()
    {
        $this->sequence->push('1');
        $this->sequence->push('a');
        $this->sequence->push('4');

        $concatStr = '';
        foreach ($this->sequence as $element) {
            $concatStr .= $element;
        }

        $this->assertSame('1a4', $concatStr);
    }
}
