<?php

use Exan\InputParser\Drivers\FormUrlEncodedDriver;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class FormUrlEncodedDriverTest extends TestCase
{
    public function testItParsesDataWithFormEncoding()
    {
        $driver = new FormUrlEncodedDriver(true);

        /**
         * @var MockInterface&StreamInterface
         */
        $stream = Mockery::mock(StreamInterface::class);

        $stream->shouldReceive()
            ->__toString()
            ->andReturn('result=%3A%3Aresult%3A%3A');

        $result = $driver->parse($stream);

        $this->assertEquals(['result' => '::result::'], $result);
    }
}
