<?php declare(strict_types=1);

namespace Pts\Lcom\Graph;

final class GraphEdge
{

    private $from;

    private $to;

    public function __construct(GraphNode $from, GraphNode $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function getFrom(): GraphNode
    {
        return $this->from;
    }

    public function getTo(): GraphNode
    {
        return $this->to;
    }
}
