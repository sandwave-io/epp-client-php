<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp;

use Psr\Log\LoggerInterface;
use SandwaveIo\EppClient\Epp\ConnectionDriver\AbstractConnectionDriver;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Response;

final class Connection
{
    /** @var AbstractConnectionDriver */
    private $driver;

    /** @var LoggerInterface|null */
    private $logger;

    public function __construct(AbstractConnectionDriver $driver, ?LoggerInterface $logger = null)
    {
        $this->driver = $driver;
        $this->logger = $logger;

        $this->driver->connect();
    }

    public function __destruct()
    {
        $this->driver->disconnect();
    }

    public function execute(Request $request, string $requestId): Document
    {
        // Render XML
        $body = $request->renderAndAppendChildren()->toString();

        // Log outgoing request
        $this->log($body, [
            'type' => 'request',
            'transaction' => $requestId,
            'requestType' => get_class($request),
        ]);

        // Execute request
        $response = $this->driver->executeRequest($body, $requestId);

        // Log incoming response
        $this->log($body, [
            'type' => 'response',
            'transaction' => $requestId,
            'requestType' => get_class($request),
        ]);

        // Parse and return response
        return Document::fromString($response);
    }

    private function log(string $text, array $context = []): void
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->debug($text, $context);
        }
    }
}
