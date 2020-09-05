<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Services;

use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Requests\DomainCheckRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\DomainInfoRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LoginRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LogoutRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainCheckResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainInfoResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\LoginResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\LogoutResponse;

abstract class AbstractService
{
    /** @var Connection */
    private $connection;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $overrideTransactionId;

    public function __construct(Connection $connection, string $username, string $password, ?string $overrideTransactionId = null)
    {
        $this->connection = $connection;
        $this->username = $username;
        $this->password = $password;
        $this->overrideTransactionId = $overrideTransactionId;
    }

    // EPP Requests

    public function checkDomains(array $domains): DomainCheckResponse
    {
        $request = new DomainCheckRequest($domains);
        return new DomainCheckResponse($this->authenticatedRequest($request));
    }

    public function domainInfo(string $domain): DomainInfoResponse
    {
        $request = new DomainInfoRequest($domain);
        return new DomainInfoResponse($this->authenticatedRequest($request));
    }

    // Authentication

    public function login(string $username, string $password): LoginResponse
    {
        $request = new LoginRequest($username, $password);
        return new LoginResponse($this->request($request));
    }

    public function logout(): LogoutResponse
    {
        $request = new LogoutRequest();
        return new LogoutResponse($this->request($request));
    }

    protected function request(Request $request, ?string $transactionId = null): Document
    {
        $transactionId = $transactionId ?? $this->generateTransactionId();
        $request->setClientTransactionIdentifier($transactionId);
        return $this->connection->execute($request, $transactionId);
    }

    // Internal functions

    protected function authenticatedRequest(Request $request, ?string $transactionId = null): Document
    {
        $this->login($this->username, $this->password);
        $response = $this->request($request, $transactionId);
        $this->logout();

        return $response;
    }

    private function generateTransactionId(int $length = 10): string
    {
        if ($this->overrideTransactionId) {
            return $this->overrideTransactionId;
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
