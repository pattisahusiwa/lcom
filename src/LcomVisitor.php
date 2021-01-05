<?php declare(strict_types=1);

namespace Pts\Lcom;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Pts\Lcom\Graph\GraphDeduplicated;
use Pts\Lcom\Graph\GraphNode;

final class LcomVisitor extends NodeVisitorAbstract
{

    private $traverser;

    private $method;

    /** @var array<string,int> */
    private $lcom = [];

    public function __construct()
    {
        $this->method    = new MethodVisitor();
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor($this->method);
    }

    /** @return array<string,int> */
    public function getLcom(): array
    {
        return $this->lcom;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Class_ || $node instanceof Trait_) {
            $this->fetchNode($node);
        }

        return null;
    }

    private function nodeName(ClassLike $node): string
    {
        if ($node instanceof Class_ && $node->isAnonymous() === true) {
            return 'anonymous@' . spl_object_hash($node);
        }

        return $node->namespacedName->toString();
    }

    private function fetchNode(ClassLike $node): void
    {
        $name  = $this->nodeName($node);
        $graph = new GraphDeduplicated();

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassMethod) {
                if ($graph->has($stmt->name . '()') === false) {
                    $graph->insert(new GraphNode($stmt->name . '()'));
                }

                $from = $graph->get($stmt->name . '()');
                $this->method->setGraph($graph, $from);
                $this->traverser->traverse([$stmt]);
            }
        }

        // count the paths
        $paths = 0;
        foreach ($graph->all() as $node) {
            $paths += $this->traversePath($node);
        }

        $this->lcom[$name] = $paths;
    }

    private function traversePath(GraphNode $node): int
    {
        if ($node->visited() === true) {
            return 0;
        }

        $node->setVisited(true);

        foreach ($node->getAdjacents() as $adjacent) {
            $this->traversePath($adjacent);
        }

        return 1;
    }
}
