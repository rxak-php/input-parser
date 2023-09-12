<?php

namespace Exan\InputParser\Driver;

use Psr\Http\Message\StreamInterface;

interface DriverInterface
{
    public function parse(StreamInterface $stream): mixed;
}
