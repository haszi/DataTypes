<?php

declare(strict_types=1);

namespace Haszi\DataTypes\CompositeTypes\Graphs;

/**
 * A node used in graphs
 */
class GraphNode
{
    public function __construct(private mixed $data = null) {}

    public function getData(): mixed
    {
        return $this->data;
    }
}
