<?php declare(strict_types=1);

namespace Pts\Lcom\Graph;

final class GraphDeduplicated extends Graph
{

    /**
     * @var array<string,bool> list of already present edges in this graph
     */
    private $edgesMap = [];

    /** @return static */
    public function addEdge(GraphNode $from, GraphNode $to)
    {
        $key = $from->getUniqueId() . '->' . $to->getUniqueId();

        if (isset($this->edgesMap[$key]) === true) {
            return $this;
        }

        $this->edgesMap[$key] = true;

        return parent::addEdge($from, $to);
    }
}
