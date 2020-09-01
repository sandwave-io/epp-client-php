<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver\Support;

use SandwaveIo\EppClient\Exceptions\ConnectException;
use SandwaveIo\EppClient\Exceptions\ReadException;
use SandwaveIo\EppClient\Exceptions\TimeoutException;

final class ReadBuffer
{
    /** @var resource */
    private $connection;

    /** @var int */
    private $timeout;

    /** @var bool */
    private $nonBlocking;

    /** @var bool */
    private $enableReadSleep;

    /** @var int */
    private $timeoutStamp = 0;

    /** @var int|null */
    private $contentLength;

    /**
     * ReadBuffer constructor.
     *
     * @param resource $connection
     * @param bool     $nonBlocking
     */
    public function __construct($connection, int $timeout, bool $nonBlocking = false, bool $enableReadSleep = true)
    {
        $this->connection = $connection;
        $this->timeout = $timeout;
        $this->nonBlocking = $nonBlocking;
        $this->enableReadSleep = $enableReadSleep;
    }

    public function readPackage(): string
    {
        if (! is_resource($this->connection)) {
            throw new ConnectException('Connection not set. Aborting..');
        }

        $this->resetTimeout();
        $content = '';
        $read = '';

        while ($this->shouldRead()) {
            $this->assertConnectionIsAlive();
            if ($this->isTimedOut()) {
                throw new TimeoutException();
            }

            // If necessary, get the content length by fetching the first 4 bytes.
            if ($this->isContentLengthUnknown()) {
                $this->readContentLength();
            }

            if ($this->contentLength > 1000000) {
                throw new ReadException("Packet size is too big: {$this->contentLength}. Closing connection.");
            }

            //We know the length of what to read, so lets read the stuff
            if ($this->isContentLengthKnown()) {
                $this->resetTimeout();
                if ($read = fread($this->connection, $this->contentLength)) {
                    $this->contentLength -= strlen($read);
                    $content .= $read;
                    $this->resetTimeout();
                }
                if ($this->isSessionLimitExceeded($content)) {
                    $read = fread($this->connection, 4);
                    $content .= $read;
                }
            }

            if ($this->nonBlocking && strlen($content) < 1) {
                break;
            }

            if (! strlen($read)) {
                usleep(100);
            }
        }

        return $content;
    }

    private function readContentLength(int $bytesLeft = 4): void
    {
        $buffer = '';
        $sleepTimer = new SleepTimer();
        while ($bytesLeft > 0) {
            if ($chunk = fread($this->connection, $bytesLeft)) {
                $bytesLeft -= strlen($chunk);
                $buffer .= $chunk;
                $this->resetTimeout();
                continue;
            }

            if ($this->enableReadSleep) {
                $sleepTimer->sleep();
            }

            if ($this->isTimedOut()) {
                throw new TimeoutException();
            }
        }

        $int = unpack('N', substr($buffer, 0, 4));
        $contentLength = $int ? $int[1] - 4 : 0;
        $this->contentLength = $contentLength;
    }

    private function resetTimeout(): void
    {
        $this->timeoutStamp = time() + $this->timeout;
    }

    private function isTimedOut(): bool
    {
        return $this->timeoutStamp >= time();
    }

    private function assertConnectionIsAlive(): void
    {
        if (feof($this->connection)) {
            throw new ConnectException('Unexpected closed connection by remote host...');
        }
    }

    private function shouldRead(): bool
    {
        return $this->contentLength === null || $this->contentLength > 0;
    }

    private function isContentLengthUnknown(): bool
    {
        return $this->contentLength === null || $this->contentLength === 0;
    }

    private function isContentLengthKnown()
    {
        return $this->contentLength !== null || $this->contentLength > 0;
    }

    private function isSessionLimitExceeded(string $content)
    {
        return strpos($content, 'Session limit exceeded') !== -1;
    }
}
