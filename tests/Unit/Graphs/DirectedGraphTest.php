<?php

declare(strict_types=1);

namespace Haszi\DataTypes\Test\UnitTest;

use Haszi\DataTypes\CompositeTypes\Graphs\DirectedGraph;
use Haszi\DataTypes\CompositeTypes\Graphs\GraphNode;
use PHPUnit\Framework\TestCase;

final class DirectedGraphTest extends TestCase
{
    private ?DirectedGraph $graph;

    public function setUp(): void
    {
        $this->graph = new DirectedGraph();
    }

    public function testGraphGetsCreated()
    {
        $this->assertInstanceOf(DirectedGraph::class, $this->graph);
    }

    public function testIsEmptyCountAndCountEdgesReturnCorrectResultForEmptyGraph()
    {
        $this->assertTrue($this->graph->isEmpty());

        $this->assertSame(0, $this->graph->count());

        $this->assertSame(0, $this->graph->countEdges());
    }

    public function testNodeGetsAdded()
    {
        $firstNodeData = 'First node';
        $node1 = new GraphNode($firstNodeData);

        $this->graph->addNode($node1);

        foreach ($this->graph as $node => $adjacencyList) {
            $this->assertSame($node1, $node);
            $this->assertSame($firstNodeData, $node->getData());
        }
    }

    public function testContainsReturnsCorrectResult()
    {
        $node = new GraphNode('A node');

        $this->assertFalse($this->graph->contains($node));

        $this->graph->addNode($node);

        $this->assertTrue($this->graph->contains($node));
    }

    public function testAddEdgeAddsEdgeCorrectly()
    {
        $node1 = new GraphNode('1');
        $node2 = new GraphNode('2');

        $this->graph->addNode($node1);
        $this->graph->addNode($node2);

        $this->assertTrue($this->graph->contains($node1));
        $this->assertTrue($this->graph->contains($node2));
        $this->assertSame(0, $this->graph->countEdges());


        $this->graph->addEdge($node1, $node2, 99);

        $this->assertSame(1, $this->graph->countEdges());


        $firstNeighbor = $this->graph->getNeighbors($node1)[0][0];

        $this->assertSame($node2, $firstNeighbor);
        $this->assertSame([], $this->graph->getNeighbors($node2));
    }

    public function testClearClearsNodesAndEdges()
    {
        $node1 = new GraphNode('1');
        $node2 = new GraphNode('2');

        $this->graph->addNode($node1);
        $this->graph->addNode($node2);
        $this->graph->addEdge($node1, $node2, 99);

        $this->assertSame(2, $this->graph->count());
        $this->assertSame(1, $this->graph->countEdges());

        $this->graph->clear();

        $this->assertSame(0, $this->graph->count());
        $this->assertSame(0, $this->graph->countEdges());
    }
}
