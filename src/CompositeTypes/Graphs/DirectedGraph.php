<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Graphs;

use Haszi\DataTypes\CompositeTypes\Collection;
use Haszi\DataTypes\CompositeTypes\Graphs\GraphNode;
use \IteratorAggregate;
use \Traversable;
use \ValueError;

use function \array_key_exists;
use function \array_reduce;
use function \array_search;
use function \array_splice;
use function \count;
use function \in_array;

/**
 * A set of nodes and their corresponding connecting directed edges
 */
class DirectedGraph implements Collection, IteratorAggregate
{
    /**
     * @var array<int, GraphNode>
     */
    private array $nodes = [];

    /**
     * @var array<int, array<GraphNode>>
     */
    private array $adjacencyList = [];

    /**
     * @var array<int, array<mixed>>
     */
    private array $edges = [];

    /**
     * Returns the number of nodes in the graph
     */
    public function count(): int
    {
        return count($this->nodes);
    }

    /**
     * Returns the number of edges in the graph
     */
    public function countEdges(): int
    {
        return (int) array_reduce(
            $this->adjacencyList,
            function ($sum, $samePriorityItems) {
                return $sum += count($samePriorityItems);
            },
            0
        );
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0
                || $this->countEdges() === 0;
    }

    public function clear(): void
    {
        $this->nodes = $this->adjacencyList = $this->edges = [];
    }

    public function getIterator(): Traversable
    {
        for ($i = 0; $i < count($this->nodes); ++$i) {
            yield $this->nodes[$i] => [
                $this->adjacencyList[$i],
                $this->edges[$i]
            ];
        }
    }

    public function addNode(GraphNode $node): void
    {
        if ($this->contains($node)) {
            return;
        }

        $this->nodes[] = $node;
        $this->adjacencyList[] = [];
        $this->edges[] = [];
    }

    public function contains(GraphNode $node): bool
    {
        return in_array($node, $this->nodes, true);
    }

    public function addEdge(GraphNode $startNode, GraphNode $endNode, mixed $weight = null): void
    {
        $this->validateEdgeNodes($startNode, $endNode);

        $startNodeOffset = $this->getNodeOffset($startNode);

        if (in_array($endNode, $this->adjacencyList[$startNodeOffset], true)) {
            throw new ValueError('There is already an edge between the provided nodes');
        }

        $this->adjacencyList[$startNodeOffset][] = $endNode;
        $this->edges[$startNodeOffset][] = $weight;
    }

    private function validateEdgeNodes(GraphNode $startNode, GraphNode $endNode): void
    {
        if (! $this->contains($startNode)) {
            throw new ValueError('Start node has not been found in graph');
        }

        if (! $this->contains($endNode)) {
            throw new ValueError('End node has not been found in graph');
        }
    }

    private function getNodeOffset(GraphNode $node): int
    {
        $index = array_search($node, $this->nodes, true);

        if ($index === false) {
            throw new ValueError('Node has not been found in graph');
        }

        return (int) $index;
    }

    /**
     * Returns whether there is a directed edge
     * from the star node pointing to the end node
     */
    public function isAdjecent(GraphNode $startNode, GraphNode $endNode): bool
    {
        $startNodeOffset = $this->getNodeOffset($startNode);

        return in_array($endNode, $this->adjacencyList[$startNodeOffset], true);
    }

    /**
     * @return array<int, array{0: GraphNode, 1: mixed}>
     */
    public function getNeighbors(GraphNode $node): array
    {
        $nodeOffset = $this->getNodeOffset($node);

        $neighbors = [];
        for ($i = 0; $i < count($this->adjacencyList[$nodeOffset]); ++$i) {
            $neighbors[] = [
                $this->adjacencyList[$nodeOffset][$i],
                $this->edges[$i]
            ];
        }

        return $neighbors;
    }

    public function removeNode(GraphNode $node): void
    {
        if (! $this->contains($node)) {
            throw new ValueError('Node has not been found in graph');
        }

        $nodeOffset = $this->getNodeOffset($node);

        if (! array_key_exists($nodeOffset, $this->nodes)
            || ! array_key_exists($nodeOffset, $this->adjacencyList)
            || ! array_key_exists($nodeOffset, $this->edges)) {
            throw new ValueError('Incosistent graph data');
        }

        array_splice($this->nodes, $nodeOffset, 1);
        array_splice($this->adjacencyList, $nodeOffset, 1);
        array_splice($this->edges, $nodeOffset, 1);
    }

    public function removeEdge(GraphNode $startNode, GraphNode $endNode): void
    {
        $this->validateEdgeNodes($startNode, $endNode);

        $startNodeOffset = $this->getNodeOffset($startNode);

        $endNodeOffset = $this->getEndNodeOffset($endNode, $startNodeOffset);

        array_splice($this->adjacencyList[$startNodeOffset], $endNodeOffset, 1);
        array_splice($this->edges[$startNodeOffset], $endNodeOffset, 1);
    }

    private function getEndNodeOffset(GraphNode $endNode, int $startNodeOffset): int
    {
        if (! in_array($endNode, $this->adjacencyList[$startNodeOffset], true)) {
            throw new ValueError('There is no edge from the supplied start node pointing to the end node');
        }

        $endNodeOffset = array_search(
            $endNode,
            $this->adjacencyList[$startNodeOffset],
            true
        );

        if ($endNodeOffset === false) {
            throw new ValueError('There is no edge from the supplied start node pointing to the end node');
        }

        return (int) $endNodeOffset;
    }

    public function getEdgeWeight(GraphNode $startNode, GraphNode $endNode): mixed
    {
        $this->validateEdgeNodes($startNode, $endNode);

        $startNodeOffset = $this->getNodeOffset($startNode);

        $endNodeOffset = $this->getEndNodeOffset($endNode, $startNodeOffset);

        return $this->edges[$startNodeOffset][$endNodeOffset];
    }

    public function setEdgeWeight(GraphNode $startNode, GraphNode $endNode, mixed $weight = null): void
    {
        $this->validateEdgeNodes($startNode, $endNode);

        $startNodeOffset = $this->getNodeOffset($startNode);

        $endNodeOffset = $this->getEndNodeOffset($endNode, $startNodeOffset);

        $this->edges[$startNodeOffset][$endNodeOffset] = $weight;
    }
}
