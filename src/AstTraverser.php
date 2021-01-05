<?php declare(strict_types=1);

namespace Pts\Lcom;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;

final class AstTraverser extends NodeTraverser
{

    public function __construct()
    {
        parent::__construct();
        $this->addVisitor(new NameResolver());
    }
}
