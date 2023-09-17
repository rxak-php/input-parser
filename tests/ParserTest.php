<?php

use Exan\InputParser\Driver\DriverInterface;
use Exan\InputParser\Exceptions\NoDriverException;
use Exan\InputParser\Parser;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class ParserTest extends TestCase
{
    public function testItThrowsAnExceptionIfNoParserIsFound()
    {
        $parser = Parser::withDefaultDrivers();

        /**
         * @var MockInterface&RequestInterface
         */
        $request = Mockery::mock(RequestInterface::class);

        $request->shouldReceive()
            ->getHeader()
            ->with('Content-Type')
            ->andReturns(['application/xml']);

        $this->expectException(NoDriverException::class);

        $parser->parse($request);
    }

    public function testItUsesTheAppropriateDriverForContentType()
    {
        /**
         * @var MockInterface&StreamInterface
         */
        $stream = Mockery::mock(StreamInterface::class);

        /**
         * @var MockInterface&DriverInterface
         */
        $jsonDriver = Mockery::mock(DriverInterface::class);

        $jsonDriver->shouldReceive()
            ->parse()
            ->with($stream)
            ->andReturn('::result::');

        /**
         * @var MockInterface&DriverInterface
         */
        $xmlDriver = Mockery::mock(DriverInterface::class);

        $xmlDriver->shouldNotReceive()
            ->parse();

        $parser = new Parser([
            'application/json' => $jsonDriver,
            'application/xml' => $xmlDriver,
        ]);

        /**
         * @var MockInterface&RequestInterface
         */
        $request = Mockery::mock(RequestInterface::class);

        $request->shouldReceive()
            ->getHeader()
            ->with('Content-Type')
            ->andReturns(['application/json']);

        $request->shouldReceive()
            ->getBody()
            ->andReturn($stream);

        $result = $parser->parse($request);

        $this->assertEquals('::result::', $result);
    }

    public function testItTriesToResolveWithDefaultDriverIfNoContentTypeWasSet()
    {
      /**
         * @var MockInterface&StreamInterface
         */
        $stream = Mockery::mock(StreamInterface::class);

        /**
         * @var MockInterface&DriverInterface
         */
        $jsonDriver = Mockery::mock(DriverInterface::class);

        $jsonDriver->shouldReceive()
            ->parse()
            ->with($stream)
            ->andReturn('::result::');

        /**
         * @var MockInterface&DriverInterface
         */
        $xmlDriver = Mockery::mock(DriverInterface::class);

        $xmlDriver->shouldNotReceive()
            ->parse();

        $parser = new Parser([
            'request-parser/default' => $jsonDriver,
            'application/xml' => $xmlDriver,
        ]);

        /**
         * @var MockInterface&RequestInterface
         */
        $request = Mockery::mock(RequestInterface::class);

        $request->shouldReceive()
            ->getHeader()
            ->with('Content-Type')
            ->andReturns([]);

        $request->shouldReceive()
            ->getBody()
            ->andReturn($stream);

        $result = $parser->parse($request);

        $this->assertEquals('::result::', $result);
    }
}
