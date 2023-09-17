<?php

namespace Exan\InputParser\Drivers;

use Psr\Http\Message\StreamInterface;

interface DriverInterface
{
    public function parse(StreamInterface $stream): mixed;
}
