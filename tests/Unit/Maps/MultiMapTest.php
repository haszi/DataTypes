<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Maps\MultiMap;
use \OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use \stdClass;

final class MultiMapTest extends TestCase
{
    private ?MultiMap $multiMultiMap;

    public function setUp(): void
    {
        $this->multiMultiMap = new MultiMap();
    }

    public function testMultiMapGetsCreated()
    {
        $this->assertInstanceOf(MultiMap::class, $this->multiMultiMap);
    }

    public function testIsEmptyAndCountReturnCorrectResultForEmptyMultiMap()
    {
        $this->assertTrue($this->multiMultiMap->isEmpty());

        $this->assertSame(0, $this->multiMultiMap->count());
    }

    public function testMultiMapCanBeIteratedOver()
    {
        $array = [];

        foreach ($this->multiMultiMap as $key => $value) {
            $array[] = $value;
        }

        $this->addToAssertionCount(1);
    }

    public function testInsertAddsKeyValuePairs()
    {
        $this->assertSame(0, $this->multiMultiMap->count());

        $this->multiMultiMap->insert('key1', 'value1');
        $this->multiMultiMap->insert('key2', 2);
        $this->multiMultiMap->insert(3, '3');

        $this->assertSame(3, $this->multiMultiMap->count());

        $this->multiMultiMap->insert(3, '4');

        $this->assertSame(4, $this->multiMultiMap->count());
    }

    public function testInsertOverwritesExistingValue()
    {
        $this->multiMultiMap->insert(3, '3');

        $this->assertSame(['3'], $this->multiMultiMap->get(3));

        $this->multiMultiMap->insert(3, 3);

        $this->assertSame(['3', 3], $this->multiMultiMap->get(3));
    }

    public function testContainsValueReturnsCorrectResult()
    {
        $this->multiMultiMap->insert('key1', 'value1');
        $this->multiMultiMap->insert('key2', 2);
        $this->multiMultiMap->insert(3, '3');

        $this->assertTrue($this->multiMultiMap->containsValue('value1'));
        $this->assertTrue($this->multiMultiMap->containsValue(2));
        $this->assertTrue($this->multiMultiMap->containsValue('3'));

        $this->assertFalse($this->multiMultiMap->containsValue('2'));
        $this->assertFalse($this->multiMultiMap->containsValue(3));
    }

    public function testContainsKeyReturnsCorrectResult()
    {
        $this->multiMultiMap->insert('key1', 'value1');
        $this->multiMultiMap->insert('key2', 2);
        $this->multiMultiMap->insert(3, '3');

        $this->assertTrue($this->multiMultiMap->containsKey('key1'));
        $this->assertTrue($this->multiMultiMap->containsKey('key2'));
        $this->assertTrue($this->multiMultiMap->containsKey(3));

        $this->assertFalse($this->multiMultiMap->containsKey('3'));
        $this->assertFalse($this->multiMultiMap->containsKey('key'));
    }

    public function testGetReturnsCorrectValues()
    {
        $this->multiMultiMap->insert('key1', 'value1');
        $this->multiMultiMap->insert('key2', 2);
        $this->multiMultiMap->insert(3, '3');
        $this->multiMultiMap->insert(3, '4');
        $this->multiMultiMap->insert(3, 66);

        $this->assertSame(['3', '4', 66], $this->multiMultiMap->get(3));
        $this->assertSame(['value1'], $this->multiMultiMap->get('key1'));
        $this->assertSame([2], $this->multiMultiMap->get('key2'));
    }

    public function testGetThrowsOnNonExistentKey()
    {
        $this->expectException(OutOfBoundsException::class);

        $this->multiMultiMap->get('someKey');

        $this->multiMultiMap->insert('3', 3);

        $this->expectException(OutOfBoundsException::class);

        $this->multiMultiMap->get(3);
    }

    public function testRemoveRemovesCorrectKeyValuePair()
    {
        $this->multiMultiMap->insert('key1', 'value1');
        $this->multiMultiMap->insert('key2', 2);
        $this->multiMultiMap->insert(3, '3');
        $this->multiMultiMap->insert('3', 3);

        $this->assertTrue($this->multiMultiMap->containsKey('3'));
        $this->assertTrue($this->multiMultiMap->containsKey(3));
        $this->assertTrue($this->multiMultiMap->containsValue('3'));
        $this->assertTrue($this->multiMultiMap->containsValue(3));

        $this->multiMultiMap->remove(3);

        $this->assertFalse($this->multiMultiMap->containsKey(3));
        $this->assertTrue($this->multiMultiMap->containsKey('3'));
        $this->assertFalse($this->multiMultiMap->containsValue('3'));
        $this->assertTrue($this->multiMultiMap->containsValue(3));
    }

    /**
     * @dataProvider iteratorData
     */
    public function testIteratorReturnsKeysValues(
        $expectedKey,
        $expectedValues
    )
    {
        foreach ($expectedValues as $value) {
            $this->multiMultiMap->insert($expectedKey, $value);
        }

        foreach ($this->multiMultiMap as $actualKey => $actualValues) {

            $this->assertSame($expectedKey, $actualKey);
            $this->assertSame($expectedValues, $actualValues);
        }
    }

    public static function iteratorData()
    {
        $anObject = new stdClass();

        return [
            [
                'key' => 'key1',
                'values' => ['value1', 2, false],
            ],
            [
                'key' => 'key2',
                'values' => [2],
            ],
            [
                'key' => 3,
                'values' => ['3'],
            ],
            [
                'key' => $anObject,
                'values' => ['object of class stdClass', 345, false]
            ],
            [
                'key' => $anObject,
                'values' => [$anObject, null, 2, 'string', 1.2]
            ],
        ];
    }
}
