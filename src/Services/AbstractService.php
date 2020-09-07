<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Services;

use Carbon\Carbon;
use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainCheckRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainCreateRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainDeleteRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainInfoRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainQueryTransferRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainRenewRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainRequestTransferRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainUpdateRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LoginRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LogoutRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainCheckResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainCreateResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainDeleteResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainInfoResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainQueryTransferResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainRenewResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainRequestTransferResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainUpdateResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\LoginResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\LogoutResponse;
use SandwaveIo\EppClient\Exceptions\ConnectException;

abstract class AbstractService
{
    /** @var Connection */
    private $connection;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string|null */
    private $overrideTransactionId;

    public function __construct(Connection $connection, string $username, string $password, ?string $overrideTransactionId = null)
    {
        $this->connection = $connection;
        $this->username = $username;
        $this->password = $password;
        $this->overrideTransactionId = $overrideTransactionId;
    }

    // EPP Query Requests

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

    public function domainTransferStatus(string $domain, ?string $domainPassword = null, ?string $registrantObjectId = null): DomainQueryTransferResponse
    {
        $request = new DomainQueryTransferRequest($domain, $domainPassword, $registrantObjectId);
        return new DomainQueryTransferResponse($this->authenticatedRequest($request));
    }

    // EPP Transform Requests

    /**
     * @param string                    $domain
     * @param int|null                  $period
     * @param array<string>|null        $nameservers
     * @param string|null               $registrant
     * @param array<string,string>|null $contacts    Key: type, Value: contact. Example: ['admin' => 'sh8013', 'tech' => 'sh8013'].
     * @param string|null               $password
     */
    public function createDomain(
        string $domain,
        ?int $period = null,
        ?array $nameservers = null,
        ?string $registrant = null,
        ?array $contacts = null,
        ?string $password = null
    ): DomainCreateResponse {
        $request = new DomainCreateRequest($domain, $period, $nameservers, $registrant, $contacts, $password);
        return new DomainCreateResponse($this->authenticatedRequest($request));
    }

    public function deleteDomain(string $domain): DomainDeleteResponse
    {
        $request = new DomainDeleteRequest($domain);
        return new DomainDeleteResponse($this->authenticatedRequest($request));
    }

    public function renewDomain(string $domain, Carbon $currentExpiryDate, ?int $period = null): DomainRenewResponse
    {
        $request = new DomainRenewRequest($domain, $currentExpiryDate, $period);
        return new DomainRenewResponse($this->authenticatedRequest($request));
    }

    /**
     * @param string      $domain
     * @param string      $password
     * @param string|null $repositoryObjectId ID of contact or registrant if the password belongs to either such an entity.
     * @param int|null    $period
     */
    public function transferDomain(string $domain, string $password, ?string $repositoryObjectId = null, ?int $period = null): DomainRequestTransferResponse
    {
        $request = new DomainRequestTransferRequest($domain, $password, $repositoryObjectId, $period);
        return new DomainRequestTransferResponse($this->authenticatedRequest($request));
    }

    public function addDomainNameservers(string $domain, array $nameservers): DomainUpdateResponse
    {
        $request = new DomainUpdateRequest($domain, $nameservers);
        return new DomainUpdateResponse($this->authenticatedRequest($request));
    }

    public function removeDomainNameservers(string $domain, array $nameservers): DomainUpdateResponse
    {
        $request = new DomainUpdateRequest($domain, null, null, null, $nameservers);
        return new DomainUpdateResponse($this->authenticatedRequest($request));
    }

    public function addDomainContact(string $domain, string $type, string $contact): DomainUpdateResponse
    {
        $request = new DomainUpdateRequest($domain, null, [$type => $contact]);
        return new DomainUpdateResponse($this->authenticatedRequest($request));
    }

    public function removeDomainContact(string $domain, string $type, string $contact): DomainUpdateResponse
    {
        $request = new DomainUpdateRequest($domain, null, null, null, null, [$type => $contact]);
        return new DomainUpdateResponse($this->authenticatedRequest($request));
    }

    public function addDomainStatus(string $domain, string $status, string $description): DomainUpdateResponse
    {
        $request = new DomainUpdateRequest($domain, null, null, [$status => $description]);
        return new DomainUpdateResponse($this->authenticatedRequest($request));
    }

    public function removeDomainStatus(string $domain, string $status, string $description = ''): DomainUpdateResponse
    {
        $request = new DomainUpdateRequest($domain, null, null, null, null, null, [$status => $description]);
        return new DomainUpdateResponse($this->authenticatedRequest($request));
    }

    public function updateDomainRegistrant(string $domain, string $registrant): DomainUpdateResponse
    {
        $request = new DomainUpdateRequest(
            $domain,
            null,
            null,
            null,
            null,
            null,
            null,
            $registrant
        );
        return new DomainUpdateResponse($this->authenticatedRequest($request));
    }

    public function updateDomainPassword(string $domain, string $password): DomainUpdateResponse
    {
        $request = new DomainUpdateRequest(
            $domain,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            $password
        );
        return new DomainUpdateResponse($this->authenticatedRequest($request));
    }

    // Authentication

    public function login(string $username, string $password): LoginResponse
    {
        $request  = new LoginRequest($username, $password);
        $response = new LoginResponse($this->request($request));

        if (! $response->isSuccess()) {
            throw new ConnectException("Cannot authenticate with EPP backend: [{$response->getResultCode()}] {$response->getResultMessage()}.");
        }

        return $response;
    }

    public function logout(): LogoutResponse
    {
        $request = new LogoutRequest();
        return new LogoutResponse($this->request($request));
    }

    // Internal functions

    protected function request(Request $request, ?string $transactionId = null): Document
    {
        $transactionId = $transactionId ?? $this->generateTransactionId();
        $request->setClientTransactionIdentifier($transactionId);
        return $this->connection->execute($request, $transactionId);
    }

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
