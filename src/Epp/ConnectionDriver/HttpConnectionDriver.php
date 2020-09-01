<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver;

class HttpConnectionDriver extends AbstractConnectionDriver
{
    public function executeRequest(string $request, string $requestId): string
    {
        // TODO: Implement executeRequest() method.
        return '';
    }

    public function connect(): bool
    {
        return true;
    }

    public function disconnect(): bool
    {
        return true;
    }
}
