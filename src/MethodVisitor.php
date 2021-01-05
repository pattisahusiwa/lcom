<?php declare(strict_types=1);

namespace Pts\Lcom;

use InvalidArgumentException;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\NodeVisitorAbstract;
use Pts\Lcom\Graph\Graph;
use Pts\Lcom\Graph\GraphNode;

final class MethodVisitor extends NodeVisitorAbstract
{

    /** @var Graph */
    private $graph;

    /** @var GraphNode */
    private $from;

    public function setGraph(Graph $graph, GraphNode $from): void
    {
        $this->graph = $graph;
        $this->from  = $from;
    }

    public function leaveNode(Node $node)
    {
        if (
            $node instanceof PropertyFetch
            && isset($node->var->name) === true
            && $node->var->name === 'this'
        ) {
            $name = $this->getName($node);

            if ($this->graph->has($name) === false) {
                $this->graph->insert(new GraphNode($name));
            }

            $to = $this->graph->get($name);
            $this->graph->addEdge($this->from, $to);
            return null;
        }

        if ($node instanceof MethodCall) {
            if (
                $node->var instanceof New_ === false
                && isset($node->var->name) === true
                && $this->getName($node->var) === 'this'
            ) {
                $name = $this->getName($node->name) . '()';
                if ($this->graph->has($name) === false) {
                    $this->graph->insert(new GraphNode($name));
                }
                $to = $this->graph->get($name);
                $this->graph->addEdge($this->from, $to);
            }
        }

        return null;
    }

    /**
     * @param Node|string $node
     */
    private function getName($node): string
    {
        if (is_string($node) === true) {
            return $node;
        }

        if ($node instanceof Identifier) {
            return $node->name;
        }

        if ($node instanceof PropertyFetch) {
            return $this->getName($node->name);
        }

        if ($node instanceof Variable) {
            return $this->getName($node->name);
        }

        if ($node instanceof MethodCall) {
            return $this->getName($node->name);
        }

        throw new InvalidArgumentException(sprintf('Node type %s is not supported', get_class($node)));
    }
}
