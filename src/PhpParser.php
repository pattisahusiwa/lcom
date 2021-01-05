<?php declare(strict_types=1);

namespace Pts\Lcom;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor;
use PhpParser\Parser;

final class PhpParser
{

    private $parser;

    private $traverser;

    public function __construct(Parser $parser, NodeTraverserInterface $traverser)
    {
        $this->parser    = $parser;
        $this->traverser = $traverser;
    }

    public function addVisitor(NodeVisitor $visitor): void
    {
        $this->traverser->addVisitor($visitor);
    }

    public function parse(string $fileContent): void
    {
        /** @var Stmt[] */
        $stmt = $this->parser->parse($fileContent);

        $this->traverser->traverse($stmt);
    }
}
