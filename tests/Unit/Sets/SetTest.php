<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sets\Set;
use PHPUnit\Framework\TestCase;

use function \count;

final class SetTest extends TestCase
{
    private ?Set $set;

    public function setUp(): void
    {
        $this->set = new Set();
    }

    public function testSetGetsCreated()
    {
        $this->assertInstanceOf(Set::class, $this->set);
    }

    public function testSetGetsCreatedFromConstructorArguments()
    {
        $set = new Set([1, 2, 'c', 'd', '5']);

        $this->assertSame(5, $set->count());
        $this->assertTrue($set->contains('c', '5', 2, 1, 'd'));
    }

    public function testContainReturnsCorrectResult()
    {
        $this->set->add(1, 2, 'c', 'd');

        $this->assertTrue($this->set->contains('c'));
        $this->assertFalse($this->set->contains('5'));
        $this->assertTrue($this->set->contains(2));
        $this->assertTrue($this->set->contains(1));
        $this->assertTrue($this->set->contains('d'));

        $set2 = new Set(['d', 'c', 2, 1]);

        $this->assertTrue($this->set->contains($set2));

        $set3 = new Set(['c', 2]);

        $this->assertTrue($this->set->contains($set3));

        $set4 = new Set(['c', 2, 'x']);

        $this->assertFalse($this->set->contains($set4));
    }

    public function testIsEmptyIsCorrect()
    {
        $this->assertTrue($this->set->isEmpty());
    }

    public function testEmptyCountIsZero()
    {
        $this->assertSame(0, $this->set->count());
    }

    public function testAddAddsElement()
    {
        $this->set->add(123);

        $this->assertSame(1, $this->set->count());

        $set2 = new Set([123, 456, 789]);

        $this->set->add($set2);

        $this->assertSame(3, $this->set->count());
    }

    public function testAddDoesntAddDuplicate()
    {
        $this->set->add(123, 456, 789);

        $this->assertSame(3, $this->set->count());

        $this->set->add(123, 456, 123456789);

        $this->assertSame(4, $this->set->count());
    }

    public function testClearRemovesAllElements()
    {
        $this->set->add(123, 456, 789);

        $this->set->clear();

        $this->assertTrue($this->set->isEmpty());
    }

    public function testRemoveDeletesElements()
    {
        $this->set->add(123, 456, 789, 'abc', 'def');

        $this->assertSame(5, $this->set->count());
        $this->assertTrue($this->set->contains(123, 'abc'));

        $this->set->remove(123, 'abc');

        $this->assertSame(3, $this->set->count());
        $this->assertFalse($this->set->contains(123));
        $this->assertFalse($this->set->contains('abc'));

        $set2 = new Set(['def', 456]);

        $this->set->remove($set2);

        $this->assertSame(1, $this->set->count());
        $this->assertFalse($this->set->contains(465));
        $this->assertFalse($this->set->contains('def'));
        $this->assertTrue($this->set->contains(789));
    }

    public function testSetCanBeIteratedOver()
    {
        $this->set->add('1', 'a', '4');

        $setAsArray = [];
        foreach ($this->set as $element) {
            $setAsArray[] = $element;
        }

        $this->assertSame(3, count($setAsArray));
        $this->assertContains('1', $setAsArray);
        $this->assertContains('a', $setAsArray);
        $this->assertContains('4', $setAsArray);
    }
}
