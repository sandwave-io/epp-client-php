<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver;

use SandwaveIo\EppClient\Epp\ConnectionDriver\Support\Stream;
use SandwaveIo\EppClient\Exceptions\ConnectException;

class SslConnectionDriver extends AbstractConnectionDriver
{
    /** @var bool */
    private $isBlocking;

    /** @var bool */
    private $verifyPeer;

    /** @var bool */
    private $verifyPeerName;

    /** @var Stream|null */
    private $stream;

    public function __construct(
        string $hostname,
        int $port,
        ?int $timeout = null,
        ?string $localCertificatePath = null,
        ?string $localCertificatePassword = null,
        ?bool $allowSelfSigned = null,
        bool $isBlocking = false,
        bool $verifyPeer = true,
        bool $verifyPeerName = true
    ) {
        $this->isBlocking = $isBlocking;
        $this->verifyPeer = $verifyPeer;
        $this->verifyPeerName = $verifyPeerName;

        parent::__construct($hostname, $port, $timeout, $localCertificatePath, $localCertificatePassword, $allowSelfSigned);
    }

    public function connect(): bool
    {
        $this->stream = new Stream(
            $this->hostname,
            $this->port,
            $this->timeout,
            $this->isBlocking,
            $this->verifyPeer,
            $this->verifyPeerName,
            $this->localCertificatePath,
            $this->localCertificatePassword,
            $this->allowSelfSigned
        );

        return $this->isConnected();
    }

    public function disconnect(): bool
    {
        if (! $this->isConnected()) {
            return true;
        }

        if ($this->stream) {
            $this->stream->close();
        }

        return true;
    }

    public function isConnected(): bool
    {
        return $this->stream && $this->stream->isConnected();
    }

    public function executeRequest(string $request, string $requestId): string
    {
        if ($this->stream === null || $this->stream->isConnected()) {
            throw new ConnectException('No active connection!');
        }

        $this->stream->write($request);

        $response = $this->stream->read();

        $tries = 0;
        while ($response === '' && $tries < 5) {
            $response = $this->stream->read();
            $tries++;
        }

        return $response;
    }
}
