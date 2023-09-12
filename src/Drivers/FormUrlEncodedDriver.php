<?php

namespace Exan\InputParser\Driver;

use Psr\Http\Message\StreamInterface;

class FormUrlEncodedDriver implements DriverInterface
{
    public function parse(StreamInterface $stream): mixed
    {
        $result = [];
        parse_str((string) $stream, $result);

        return $result;
    }
}
