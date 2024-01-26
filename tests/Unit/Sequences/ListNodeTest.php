<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Sequences\ListNode;
use PHPUnit\Framework\TestCase;
use \stdClass;

final class ListNodeTest extends TestCase
{
    public function testEmptyNodeGetsCreated()
    {
        $node = new ListNode();

        $this->assertInstanceOf(ListNode::class, $node);
    }

    public function testNodeGetsCreatedFromConstructorProperties()
    {
        $node1 = new ListNode(1, null);
        $node2 = new ListNode(2, $node1);

        $this->assertInstanceOf(ListNode::class, $node1);
        $this->assertInstanceOf(ListNode::class, $node2);
    }

    /**
     * @dataProvider getReturnValueProvider
     */
    public function testGetValueReturnsValue($value)
    {
        $node = new ListNode($value);

        $this->assertSame($value, $node->getValue());
    }

    public static function getReturnValueProvider()
    {
        return [
            [
                'value' => 'someValue'
            ],
            [
                'value' => null
            ],
            [
                'value' => new stdClass
            ],
        ];
    }

    public function testNodesReturnCorrectValuesAndLinkedNodes()
    {
        $node1 = new ListNode(1, null);
        $node2 = new ListNode(2, $node1);

        $this->assertSame(1, $node1->getValue());
        $this->assertNull($node1->getNextNode());

        $this->assertSame(2, $node2->getValue());
        $this->assertSame($node1, $node2->getNextNode());
    }

    public function testSetValueSetsValue()
    {
        $node = new ListNode('a');

        $this->assertSame('a', $node->getValue());

        $node->setValue('b');

        $this->assertSame('b', $node->getValue());
    }

    public function testSetNextNodeSetsNextNode()
    {
        $node1 = new ListNode('a');
        $node2 = new ListNode('b');

        $node2->setNextNode($node1);

        $this->assertSame($node1, $node2->getNextNode());
    }
}
