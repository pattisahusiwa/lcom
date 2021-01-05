<?php declare(strict_types=1);

use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use Pts\Lcom\AstTraverser;
use Pts\Lcom\LcomVisitor;
use Pts\Lcom\PhpParser;

final class LcomVisitorTest extends TestCase
{

    /** @var LcomVisitor */
    private $lcom;

    /** @var PhpParser */
    private $parser;

    protected function setUp(): void
    {
        $this->lcom = new LcomVisitor();

        $factory   = new ParserFactory();
        $parser    = $factory->create(ParserFactory::PREFER_PHP7);
        $traverser = new AstTraverser();

        $this->parser = new PhpParser($parser, $traverser);
        $this->parser->addVisitor($this->lcom);
    }

    /**
     * @dataProvider data
     */
    public function test(string $filename, string $name, int $count)
    {
        $content = file_get_contents(__DIR__ . '/_data/' . $filename);
        $this->parser->parse($content);

        $lcom = $this->lcom->getLcom();
        $this->assertSame($count, $lcom[$name]);
    }

    public function data()
    {
        return [
                ['test1.inc', 'Example', 2],
                ['test2.inc', 'BagTrait', 1],
                ['test3.inc', 'StableAbstractionsPrinciple', 3],
               ];
    }
}
