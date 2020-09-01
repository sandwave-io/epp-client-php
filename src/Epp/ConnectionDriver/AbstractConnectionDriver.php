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

    /** @var string|null */
    protected $localCertificatePath;

    /** @var string|null */
    protected $localCertificatePassword;

    /** @var bool|null */
    protected $allowSelfSigned;

    public function __construct(
        string $hostname,
        int $port,
        ?int $timeout = null,
        ?string $localCertificatePath = null,
        ?string $localCertificatePassword = null,
        ?bool $allowSelfSigned = null
    ) {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->timeout = $timeout ?? $this->timeout;
        $this->localCertificatePath = $localCertificatePath;
        $this->localCertificatePassword = $localCertificatePassword;
        $this->allowSelfSigned = $allowSelfSigned;
    }

    abstract public function connect(): bool;

    abstract public function disconnect(): bool;

    abstract public function executeRequest(string $request, string $requestId): string;
}
