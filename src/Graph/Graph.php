<?php declare(strict_types=1);

namespace Pts\Lcom\Graph;

use InvalidArgumentException;

abstract class Graph
{

    /**
     * @var GraphNode[]
     */
    private $data = [];

    /**
     * @var GraphEdge[]
     */
    private $edges = [];

    public function insert(GraphNode $node): self
    {
        $this->data[$node->getKey()] = $node;

        return $this;
    }

    /** @return static */
    public function addEdge(GraphNode $from, GraphNode $to)
    {
        $edge = new GraphEdge($from, $to);
        $from->addEdge($edge);
        $to->addEdge($edge);
        array_push($this->edges, $edge);

        return $this;
    }

    public function get(string $key): GraphNode
    {
        if ($this->has($key) === false) {
            throw new InvalidArgumentException(sprintf('Node with key %s is not found', $key));
        }

        return $this->data[$key];
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @return GraphNode[]
     */
    public function all(): array
    {
        return $this->data;
    }
}
