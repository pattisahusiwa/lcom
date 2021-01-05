<?php declare(strict_types=1);

namespace Pts\Lcom\Graph;

final class GraphNode
{

    private $data;

    private $key;

    /**
     * @var GraphEdge[]
     */
    private $edges = [];

    /** @var bool */
    private $visited = false;

    /**
     * @param mixed $data
     */
    public function __construct(string $key, $data = null)
    {
        $this->key  = $key;
        $this->data = $data;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array<string,GraphNode>
     */
    public function getAdjacents(): array
    {
        $adjacents = [];
        foreach ($this->edges as $edge) {
            if ($edge->getFrom()->getKey() !== $this->getKey()) {
                $adjacents[$edge->getFrom()->getKey()] = $edge->getFrom();
            }
            if ($edge->getTo()->getKey() !== $this->getKey()) {
                $adjacents[$edge->getTo()->getKey()] = $edge->getTo();
            }
        }

        return $adjacents;
    }

    public function addEdge(GraphEdge $edge): self
    {
        array_push($this->edges, $edge);

        return $this;
    }

    /**
     * @return string Unique id for this node independent of class name or node type
     */
    public function getUniqueId(): string
    {
        return spl_object_hash($this);
    }

    public function setVisited(bool $value): void
    {
        $this->visited = $value;
    }

    public function visited(): bool
    {
        return $this->visited;
    }
}
