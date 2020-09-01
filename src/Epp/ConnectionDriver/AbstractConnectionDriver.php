<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver;

abstract class AbstractConnectionDriver
{
    /** @var string */
    protected $hostname;

    /** @var int */
    protected $port;

    /** @var int */
    protected $timeout = 10;

    public function __construct(string $hostname, int $port, ?int $timeout = null)
    {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->timeout = $timeout ?? $this->timeout;
    }

    abstract public function connect(): bool;

    abstract public function disconnect(): bool;

    abstract public function executeRequest(string $request, string $requestId): string;
}
