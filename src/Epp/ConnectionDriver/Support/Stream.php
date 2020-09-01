<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver\Support;

use SandwaveIo\EppClient\Exceptions\ConnectException;
use SandwaveIo\EppClient\Exceptions\TimeoutException;

class Stream
{
    /** @var int */
    private $timeout;

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

    /**
     * @var resource
     */
    private $connection;

    public function __construct(
        string $hostname,
        int $port,
        int $timeout = 10,
        bool $isBlocking = false,
        bool $verifyPeer = true,
        bool $verifyPeerName = true,
        ?string $localCertificatePath = null,
        ?string $localCertificatePassword = null,
        ?bool $allowSelfSigned = null
    ) {
        $this->timeout = $timeout;
        $this->isBlocking = $isBlocking;
        $this->verifyPeer = $verifyPeer;
        $this->verifyPeerName = $verifyPeerName;
        $this->localCertificatePath = $localCertificatePath;
        $this->localCertificatePassword = $localCertificatePassword;
        $this->allowSelfSigned = $allowSelfSigned;

        $connection = stream_socket_client(
            "{$hostname}:{$port}",
            $errorNumber,
            $errorMessage,
            $timeout,
            STREAM_CLIENT_CONNECT,
            $this->createStreamContext()
        );

        if (! is_resource($connection)) {
            throw new ConnectException("Could not instantiate EPP connection to {$hostname}:{$port}. Reason: [{$errorNumber}]: $errorMessage");
        }

        stream_set_blocking($connection, $this->isBlocking);
        stream_set_timeout($connection, $timeout);

        $this->connection = $connection;
    }

    public function close(): void
    {
        if (! $this->isConnected()) {
            return;
        }
        fclose($this->connection);

        unset($this->connection);
    }

    public function isConnected(): bool
    {
        return is_resource($this->connection);
    }

    public function read(bool $nonBlocking = false): string
    {
        if (! $this->isConnected()) {
            return '';
        }

        $buffer = new ReadBuffer($this->connection, $this->timeout, $nonBlocking);
        try {
            $content = $buffer->readPackage();
        } catch (TimeoutException $e) {
            return '';
        }

        // Consume the first package from the connection.
        $this->read();

        return $content;
    }

    public function write(string $content): void
    {
        if (! $this->isConnected()) {
            return;
        }

        $buffer = new WriteBuffer($this->connection, $content);
        $buffer->write();
    }

    /** @return resource */
    private function createStreamContext()
    {
        $context = stream_context_create();
        stream_context_set_option($context, 'ssl', 'verify_peer', $this->verifyPeer);
        stream_context_set_option($context, 'ssl', 'verify_peer_name', $this->verifyPeerName);

        if (! $this->localCertificatePath) {
            return $context;
        }

        stream_context_set_option($context, 'ssl', 'local_cert', $this->localCertificatePath);

        if ($this->localCertificatePassword && strlen($this->localCertificatePassword) > 0) {
            stream_context_set_option($context, 'ssl', 'passphrase', $this->localCertificatePassword);
        }

        if ($this->allowSelfSigned === null) {
            stream_context_set_option($context, 'ssl', 'verify_peer', $this->verifyPeer);
            return $context;
        }

        stream_context_set_option($context, 'ssl', 'allow_self_signed', $this->allowSelfSigned);
        stream_context_set_option($context, 'ssl', 'verify_peer', false);

        return $context;
    }
}
