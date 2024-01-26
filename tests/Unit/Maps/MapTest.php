<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Maps\Map;
use \OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use \stdClass;

final class MapTest extends TestCase
{
    private ?Map $map;

    public function setUp(): void
    {
        $this->map = new Map();
    }

    public function testMapGetsCreated()
    {
        $this->assertInstanceOf(Map::class, $this->map);
    }

    public function testIsEmptyAndCountReturnCorrectResultForEmptyMap()
    {
        $this->assertTrue($this->map->isEmpty());

        $this->assertSame(0, $this->map->count());
    }

    public function testMapCanBeIteratedOver()
    {
        $array = [];

        $this->map->insert('key1', 'value1');
        $this->map->insert('key2', 2);
        $this->map->insert(3, '3');

        foreach ($this->map as $key => $value) {
            $array[] = $value;
        }

        $this->addToAssertionCount(1);
    }

    public function testInsertAddsKeyValuePairs()
    {
        $this->assertSame(0, $this->map->count());

        $this->map->insert('key1', 'value1');
        $this->map->insert('key2', 2);
        $this->map->insert(3, '3');

        $this->assertSame(3, $this->map->count());

        $this->map->insert(3, '4');

        $this->assertSame(3, $this->map->count());
    }

    public function testInsertOverwritesExistingValue()
    {
        $this->map->insert(3, '3');

        $this->assertSame('3', $this->map->get(3));

        $this->map->insert(3, 3);

        $this->assertSame(3, $this->map->get(3));
    }

    public function testContainsValueReturnsCorrectResult()
    {
        $this->map->insert('key1', 'value1');
        $this->map->insert('key2', 2);
        $this->map->insert(3, '3');

        $this->assertTrue($this->map->containsValue('value1'));
        $this->assertTrue($this->map->containsValue(2));
        $this->assertTrue($this->map->containsValue('3'));

        $this->assertFalse($this->map->containsValue('2'));
        $this->assertFalse($this->map->containsValue(3));
    }

    public function testContainsKeyReturnsCorrectResult()
    {
        $this->map->insert('key1', 'value1');
        $this->map->insert('key2', 2);
        $this->map->insert(3, '3');

        $this->assertTrue($this->map->containsKey('key1'));
        $this->assertTrue($this->map->containsKey('key2'));
        $this->assertTrue($this->map->containsKey(3));

        $this->assertFalse($this->map->containsKey('3'));
        $this->assertFalse($this->map->containsKey('key'));
    }

    public function testGetReturnsCorrectValues()
    {
        $this->map->insert('key1', 'value1');
        $this->map->insert('key2', 2);
        $this->map->insert(3, '3');

        $this->assertSame('3', $this->map->get(3));
        $this->assertSame('value1', $this->map->get('key1'));
        $this->assertSame(2, $this->map->get('key2'));
    }

    public function testGetThrowsOnNonExistentKey()
    {
        $this->expectException(OutOfBoundsException::class);

        $this->map->get('someKey');

        $this->map->insert('3', 3);

        $this->expectException(OutOfBoundsException::class);

        $this->map->get(3);
    }

    public function testRemoveRemovesCorrectKeyValuePair()
    {
        $this->map->insert('key1', 'value1');
        $this->map->insert('key2', 2);
        $this->map->insert(3, '3');
        $this->map->insert('3', 3);

        $this->assertTrue($this->map->containsKey('3'));
        $this->assertTrue($this->map->containsKey(3));
        $this->assertTrue($this->map->containsValue('3'));
        $this->assertTrue($this->map->containsValue(3));

        $this->map->remove(3);

        $this->assertFalse($this->map->containsKey(3));
        $this->assertTrue($this->map->containsKey('3'));
        $this->assertFalse($this->map->containsValue('3'));
        $this->assertTrue($this->map->containsValue(3));
    }

    /**
     * @dataProvider iteratorData
     */
    public function testIteratorReturnsKeysValues(
        $expectedKey,
        $expectedValue
    )
    {
        $this->map->insert($expectedKey, $expectedValue);

        foreach ($this->map as $actualKey => $actualValue) {
            $this->assertSame($expectedKey, $actualKey);
            $this->assertSame($expectedValue, $actualValue);
        }
    }

    public static function iteratorData()
    {
        $anObject = new stdClass();

        return [
            [
                'key' => 'key1',
                'value' => 'value1',
            ],
            [
                'key' => 'key2',
                'value' => 2,
            ],
            [
                'key' => 3,
                'value' => '3',
            ],
            [
                'key' => $anObject,
                'value' => 'object of class stdClass'
            ],
            [
                'key' => $anObject,
                'value' => $anObject
            ],
        ];
    }
}
