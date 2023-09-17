<?php

namespace Exan\InputParser\Drivers;

use Psr\Http\Message\StreamInterface;

class JsonEncodedDriver implements DriverInterface
{
    public function __construct(private bool $associative = true)
    {
    }

    public function parse(StreamInterface $stream): mixed
    {
        return json_decode((string) $stream, $this->associative);
    }
}
