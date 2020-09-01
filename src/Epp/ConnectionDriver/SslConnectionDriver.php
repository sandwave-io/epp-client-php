<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver;

use Hoa\File\Write;
use SandwaveIo\EppClient\Epp\ConnectionDriver\Support\ReadBuffer;
use SandwaveIo\EppClient\Epp\ConnectionDriver\Support\WriteBuffer;
use SandwaveIo\EppClient\Exceptions\ConnectException;
use SandwaveIo\EppClient\Exceptions\TimeoutException;

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

    /** @var bool|null  */
    private $allowSelfSigned;

    /** @var resource|null */
    private $connection;

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
        $connection = stream_socket_client(
            "{$this->hostname}:{$this->port}",
            $errorNumber,
            $errorMessage,
            $this->timeout,
            STREAM_CLIENT_CONNECT,
            $this->createStreamContext()
        );

        if (! is_resource($connection)) {
            throw new ConnectException("Could not instantiate EPP connection to {$this->hostname}:{$this->port}. Reason: [{$errorNumber}]: $errorMessage");
        }

        stream_set_blocking($connection, $this->isBlocking);
        stream_set_timeout($connection, $this->timeout);

        $this->connection = $connection;

        return $this->isConnected();
    }

    public function disconnect(): bool
    {
        if (! $this->isConnected()) {
            return true;
        }

        fclose($this->connection);
        $this->connection = null;

        // Consume the first package from the connection.
        $this->read();

        return true;
    }

    public function executeRequest(string $request, string $requestId): string
    {
        // TODO: Implement executeRequest() method.
    }

    public function isConnected(): bool
    {
        return is_resource($this->connection);
    }

    private function read(bool $nonBlocking = false): string
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

        return $content;
    }

    private function write(string $content): void
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
