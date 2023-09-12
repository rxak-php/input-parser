<?php

namespace Exan\InputParser;

use DriverInterface;
use Exan\InputParser\Driver\FormUrlEncodedDriver;
use Exan\InputParser\Driver\JsonEncodedDriver;
use Exan\InputParser\Exceptions\NoDriverException;
use Psr\Http\Message\RequestInterface;

class Parser
{
    public static function withDefaultDrivers(): static
    {
        return new static([
            'application/x-www-form-urlencoded' => new FormUrlEncodedDriver(),
            'application/json' => new JsonEncodedDriver(),
        ]);
    }

    /**
     * @var DriverInterface[]
     */
    public function __construct(private array $drivers = [])
    {
    }

    public function parse(RequestInterface $request): mixed
    {
        $header = $request->getHeader('Content-Type');

        $contentType = count($header) > 0
            ? $header[0]
            : 'request-parser/default';

        if (!isset($this->drivers[$contentType])) {
            throw new NoDriverException(sprintf('No driver found for Content-Type `%s`', $contentType));
        }

        return $this->drivers[$contentType]->parse($request->getBody());
    }
}
