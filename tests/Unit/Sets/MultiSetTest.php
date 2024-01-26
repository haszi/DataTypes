<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sets\MultiSet;
use PHPUnit\Framework\TestCase;
use stdClass;

final class MultiSetTest extends TestCase
{
    private ?MultiSet $multiSet;

    public function setUp(): void
    {
        $this->multiSet = new MultiSet();
    }

    public function testMultiSetGetsCreated()
    {
        $this->assertInstanceOf(MultiSet::class, $this->multiSet);
    }

    /**
     * @dataProvider validElements
     */
    public function testMultiSetGetsCreatedFromConstructorArguments(
        $addElements,
        $containsElements
    )
    {
        $multiSet = new MultiSet($addElements);

        $elementCount = count($addElements);

        $this->assertSame($elementCount, $multiSet->count());
        foreach ($containsElements as $value) {
            $this->assertTrue($multiSet->contains($value));
        }
    }

    public static function validElements()
    {
        $object = new stdClass();
        return [
            [
                'addElements' => [1, 2, 'c', 'd', '5'],
                'containsElements' => ['c', '5', 2, 1, 'd']
            ],
            [
                'addElements' => [1, 2, [1], $object],
                'containsElements' => [[1], 2, 1, $object]
            ],
        ];
    }

    /**
     * @dataProvider individualValidElements
     */
    public function testContainReturnsCorrectResultFromIndividualElements(
        $addElements,
        $containsElements,
        $doesntContainElements
    )
    {
        foreach ($addElements as $addValue) {
            $this->multiSet->add($addValue);
        }

        foreach ($containsElements as $value) {
            $this->assertTrue($this->multiSet->contains($value));
        }

        foreach ($doesntContainElements as $value) {
            $this->assertFalse($this->multiSet->contains($value));
        }
    }

    public static function individualValidElements()
    {
        $object = new stdClass();
        return [
            [
                'addElements' => [1, 2, 'c', 'd'],
                'containsElements' => ['c', 2, 1, 'd'],
                'doesntContainElements' => ['5'],
            ],
            [
                'addElements' => [1, 2, [1], $object],
                'containsElements' => [[1], 2, 1, $object],
                'doesntContainElements' => ['5'],
            ],
        ];
    }

    /**
     * @dataProvider iterablesForComparison
     */
    public function testContainReturnsCorrectResultFromIterables(
        $baseSet,
        $subSet,
        $notSubset
    ) {
        foreach ($baseSet as $value) {
            $this->multiSet->add($value);
        }

        $set = $subSet;
        $expected = true;

        if ($notSubset !== []) {
            $set = $notSubset;
            $expected = false;
        }

        $multiSet2 = new MultiSet($set);

        $this->assertSame($expected, $this->multiSet->contains($multiSet2));
    }

    public static function iterablesForComparison()
    {
        $anObject = new stdClass();

        return [
            [
                'baseSet' => [1, 2, 'c', 'd', '2', 1],
                'subSet' => ['d', 'c', 2, 1],
                'notSubset' => [],
            ],
            [
                'baseSet' => [1, 2, 'c', 'd', '2', 1],
                'subSet' => ['c', 2],
                'notSubset' => [],
            ],
            [
                'baseSet' => [1, 2, 'c', 'd', '2', 1, $anObject],
                'subSet' => ['c', 2, $anObject],
                'notSubset' => [],
            ],
            [
                'baseSet' => [1, 2, 'c', 'd', '2', 1],
                'subSet' => [],
                'notSubset' => ['c', 2, 'x', $anObject],
            ],
        ];
    }

    public function testIsEmptyIsCorrect()
    {
        $this->assertTrue($this->multiSet->isEmpty());
    }

    public function testEmptyCountIsZero()
    {
        $this->assertSame(0, $this->multiSet->count());
    }

    /**
     * @dataProvider elementsToAdd
     */
    public function testAddAddsAnotherMultiSet(
        $baseSet,
        $otherSet,
        $resultingCount,
        $resultingSet
    ) {
        foreach ($baseSet as $element) {
            $this->multiSet->add($element);
        }

        $multiSet2 = new MultiSet($otherSet);

        $this->multiSet->add($multiSet2);

        $this->assertSame($resultingCount, $this->multiSet->count());
        $this->assertTrue($this->multiSet->contains($resultingSet));
    }

    public static function elementsToAdd()
    {
        $anObject = new stdClass();

        return [
            [
                'baseSet' => [123],
                'otherSet' => [123, 456, 789],
                'resultingCount' => 4,
                'resultingSet' => [123, 456, 789],
            ],
            [
                'baseSet' => [123],
                'otherSet' => [$anObject, 'a', 456, false],
                'resultingCount' => 5,
                'resultingSet' => [false, $anObject, 456, 'a', 123],
            ],
            [
                'baseSet' => [123],
                'otherSet' => ['1', 1, 'a', false, $anObject, [1]],
                'resultingCount' => 7,
                'resultingSet' => [123, '1', 1, 'a', false, $anObject],
            ],
        ];
    }

    public function testClearRemovesAllElements()
    {
        $this->multiSet->add(123, 456, 789);

        $this->multiSet->clear();

        $this->assertTrue($this->multiSet->isEmpty());
    }

    public function testRemoveDeletesIndividualElements()
    {
        $this->multiSet->add(123, 456, 789, 'abc', 'def');

        $this->assertSame(5, $this->multiSet->count());
        $this->assertTrue($this->multiSet->contains(123, 'abc'));

        $this->multiSet->remove(123, 'abc');

        $this->assertSame(3, $this->multiSet->count());
        $this->assertFalse($this->multiSet->contains(123));
        $this->assertFalse($this->multiSet->contains('abc'));
    }

    public function testRemoveDeletesElementsFromIterables()
    {
        $this->multiSet->add(123, 456, 789, 'abc', 'def', 'ghi');

        $multiSet2 = new MultiSet(['def']);

        $this->multiSet->remove($multiSet2);

        $this->assertSame(5, $this->multiSet->count());

        $multiSet3 = new MultiSet(['abc', 456]);

        $this->multiSet->remove($multiSet3);

        $this->assertSame(3, $this->multiSet->count());
        $this->assertFalse($this->multiSet->contains(456));
        $this->assertFalse($this->multiSet->contains('def'));
        $this->assertFalse($this->multiSet->contains('abc'));
        $this->assertTrue($this->multiSet->contains(123));
        $this->assertTrue($this->multiSet->contains(789));
    }

    /**
     * @dataProvider iteratorTestData
     */
    public function testMultiSetCanBeIteratedOver(
        $data,
        $count,
        $containsElements,
        $resultingSet
    ) {
        foreach ($data as $element) {
            $this->multiSet->add($element);
        }

        $this->assertSame($count, $this->multiSet->count());

        $this->assertTrue($this->multiSet->contains($containsElements));

        $resultSetCount = 0;

        foreach ($this->multiSet as $key => $value) {
            $this->assertSame($resultingSet[$resultSetCount], $value);
            ++$resultSetCount;
        }
    }

    public static function iteratorTestData()
    {
        $anObject = new stdClass();

        return [
            [
                'data' => ['1', 1, 'a', false, $anObject, [1]],
                'count' => 6,
                'containsElements' => ['1', 1, 'a', false, $anObject],
                'resultingSet' => ['1', 1, 1, 'a', false, $anObject],
            ],
        ];
    }

}
