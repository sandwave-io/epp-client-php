<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver;

use Hoa\File\Write;
use SandwaveIo\EppClient\Epp\ConnectionDriver\Support\ReadBuffer;
use SandwaveIo\EppClient\Epp\ConnectionDriver\Support\Stream;
use SandwaveIo\EppClient\Epp\ConnectionDriver\Support\WriteBuffer;
use SandwaveIo\EppClient\Exceptions\ConnectException;
use SandwaveIo\EppClient\Exceptions\TimeoutException;
use Webmozart\Assert\Assert;

class SslConnectionDriver extends AbstractConnectionDriver
{
    /** @var bool */
    private $isBlocking;

    /** @var bool */
    private $verifyPeer;

    /** @var bool */
    private $verifyPeerName;

    /** @var string|null */
    private $localCertificatePath;

    /** @var string|null */
    private $localCertificatePassword;

    /** @var bool|null */
    private $allowSelfSigned;

    /** @var Stream|null */
    private $stream;

    public function __construct(
        string $hostname,
        int $port,
        ?int $timeout = null,
        bool $isBlocking = false,
        bool $verifyPeer = true,
        bool $verifyPeerName = true,
        ?string $localCertificatePath = null,
        ?string $localCertificatePassword = null,
        ?bool $allowSelfSigned = null
    ) {
        $this->isBlocking = $isBlocking;
        $this->verifyPeer = $verifyPeer;
        $this->verifyPeerName = $verifyPeerName;
        $this->localCertificatePath = $localCertificatePath;
        $this->localCertificatePassword = $localCertificatePassword;
        $this->allowSelfSigned = $allowSelfSigned;

        parent::__construct($hostname, $port, $timeout);
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
        // TODO: Implement executeRequest() method.
        return '';
    }
}
