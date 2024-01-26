<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Graphs\GraphNode;
use PHPUnit\Framework\TestCase;
use \stdClass;

final class GraphNodeTest extends TestCase
{
    public function testEmptyNodeGetsCreated()
    {
        $node = new GraphNode();

        $this->assertInstanceOf(GraphNode::class, $node);
    }

    public function testNodeAcceptsConstructorProperty()
    {
        $node = new GraphNode('Node label');

        $this->assertInstanceOf(GraphNode::class, $node);
    }

    /**
     * @dataProvider provideData
     */
    public function testGetReturnsCorrectData($label)
    {
        $node = new GraphNode($label);

        $this->assertSame($label, $node->getData());
    }

    public static function provideData()
    {
        return [
            [null],
            [false],
            [true],
            [''],
            [' '],
            ['some label'],
            [0],
            [PHP_INT_MIN],
            [PHP_INT_MAX],
            [new stdClass()],
            [[]],
            [[1]],
            [['a']],
        ];
    }
}
