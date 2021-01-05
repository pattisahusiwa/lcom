# lcom

> Library to calculate Lack of Cohesion of Methods (LCOM) metric

This source code is extracted from `phpmetrics` with some modifications.

## Usage
```php
<?php

use PhpParser\ParserFactory;
use Pts\Lcom\AstTraverser;
use Pts\Lcom\PhpParser;

// Define LCOM visitor
$lcom = new LcomVisitor();

// Define parser and traverser
$factory   = new ParserFactory();
$parser    = $factory->create(ParserFactory::PREFER_PHP7);
$traverser = new AstTraverser();

// Construct PHP parser and pass LCOM visitor
$phpParser = new PhpParser($parser, $traverser);
$phpParser->addVisitor($lcom);
```
