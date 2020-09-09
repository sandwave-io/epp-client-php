<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Services;

use Carbon\Carbon;
use SandwaveIo\EppClient\Epp\Extensions\Requests\Sidn\SidnContactCreateRequest;
use SandwaveIo\EppClient\Epp\Extensions\Requests\Sidn\SidnDomainRenewRequest;
use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Domain\DomainRenewRequest;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;
use SandwaveIo\EppClient\Epp\Rfc\Responses\ContactCreateResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\DomainRenewResponse;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ContactPostalInfo;
use Webmozart\Assert\Assert;

final class SidnService extends AbstractService
{
    protected function request(Request $request, ?string $transactionId = null): Document
    {
        $request->addEppExtension('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        return parent::request($request, $transactionId);
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
}
