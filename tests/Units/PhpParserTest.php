<?php declare(strict_types=1);

use PhpParser\Error;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use Pts\Lcom\AstTraverser;
use Pts\Lcom\PhpParser;

final class PhpParserTest extends TestCase
{

    /** @var PhpParser */
    private $parser;

    protected function setUp(): void
    {
        $factory   = new ParserFactory();
        $parser    = $factory->create(ParserFactory::PREFER_PHP7);
        $traverser = new AstTraverser();

        $this->parser = new PhpParser($parser, $traverser);
    }

    public function testShouldThrowsError()
    {
        $this->expectException(Error::class);
        $this->parser->parse('<?php declare');
    }

    public function testShouldNotThrowAnyErrors()
    {
        $content = file_get_contents(__DIR__ . '/_data/test1.inc');
        $this->parser->parse($content);
        $this->assertTrue(true);
    }
}
