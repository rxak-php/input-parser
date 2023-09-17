<?php

use Exan\InputParser\Drivers\JsonEncodedDriver;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class JsonEncodedDriverTest extends TestCase
{
    public function testItParsesAssociativeJson()
    {
        $data = ['result' => '::result::'];

        $driver = new JsonEncodedDriver(true);

        /**
         * @var MockInterface&StreamInterface
         */
        $stream = Mockery::mock(StreamInterface::class);

        $stream->shouldReceive()
            ->__toString()
            ->andReturn(json_encode($data));

        $result = $driver->parse($stream);

        $this->assertEquals($data, $result);
    }

    public function testItParsesNonAssociativeJson()
    {
        $data = ['result' => '::result::'];

        $driver = new JsonEncodedDriver(false);

        /**
         * @var MockInterface&StreamInterface
         */
        $stream = Mockery::mock(StreamInterface::class);

        $stream->shouldReceive()
            ->__toString()
            ->andReturn(json_encode($data));

        $result = $driver->parse($stream);

        $this->assertEquals((object) $data, $result);
    }
}
