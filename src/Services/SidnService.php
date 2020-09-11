<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Services;

use Carbon\Carbon;
use SandwaveIo\EppClient\Epp\Extensions\Requests\Sidn\SidnContactCreateRequest;
use SandwaveIo\EppClient\Epp\Extensions\Requests\Sidn\SidnDomainRenewRequest;
use SandwaveIo\EppClient\Epp\Extensions\Responses\Sidn\SidnDomainInfoResponse;
use SandwaveIo\EppClient\Epp\Extensions\Responses\Sidn\SidnDomainQueryTransferResponse;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainInfoRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainQueryTransferRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;
use SandwaveIo\EppClient\Epp\Rfc\Responses\ContactCreateResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainInfoResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainQueryTransferResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainRenewResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ContactPostalInfo;
use Webmozart\Assert\Assert;

final class SidnService extends AbstractService
{
    /** @return SidnDomainInfoResponse */
    public function domainInfo(string $domain): DomainInfoResponse
    {
        $request = new DomainInfoRequest($domain);
        return new SidnDomainInfoResponse($this->authenticatedRequest($request));
    }

    /** @return SidnDomainQueryTransferResponse */
    public function domainTransferStatus(string $domain, ?string $domainPassword = null, ?string $registrantObjectId = null): DomainQueryTransferResponse
    {
        $request = new DomainQueryTransferRequest($domain, $domainPassword, $registrantObjectId);
        return new SidnDomainQueryTransferResponse($this->authenticatedRequest($request));
    }

    public function renewDomain(string $domain, Carbon $currentExpiryDate, ?int $period = null): DomainRenewResponse
    {
        $request = new SidnDomainRenewRequest($domain, $currentExpiryDate, $period);
        return new DomainRenewResponse($this->authenticatedRequest($request));
    }

    public function createContact(
        string $contact,
        string $email,
        string $password,
        ?ContactPostalInfo $internationalAddress = null,
        ?ContactPostalInfo $localAddress = null,
        ?string $voice = null,
        ?string $fax = null,
        ?array $disclosure = null,
        ?bool $doDisclose = null
    ): ContactCreateResponse {
        if ($internationalAddress) {
            Assert::notNull($internationalAddress->name, 'An address must have a set name when creating a contact.');
        }
        if ($localAddress) {
            Assert::notNull($localAddress->name, 'An address must have a set name when creating a contact.');
        }

        $request = new SidnContactCreateRequest(
            $contact,
            $email,
            $password,
            $internationalAddress,
            $localAddress,
            $voice,
            $fax,
            $disclosure,
            $doDisclose
        );
        return new ContactCreateResponse($this->authenticatedRequest($request));
    }

    protected function request(Request $request, ?string $transactionId = null): Document
    {
        $request->addEppNamespace('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        return parent::request($request, $transactionId);
    }
}
