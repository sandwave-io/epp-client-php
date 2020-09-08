<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses\Objects;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\Address\ContactAddress;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\Address\ContactCity;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\Address\ContactCountryCode;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\Address\ContactPostalCode;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\Address\ContactStateOrProvince;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\Address\ContactStreet;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactOrganization;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactPostalInfo as ContactPostalInfoElement;
use SandwaveIo\EppClient\Exceptions\EppXmlException;
use SandwaveIo\EppClient\Exceptions\InvalidArgumentException;
use Webmozart\Assert\Assert;

class ContactPostalInfo
{
    const TYPE_INTERNATIONALIZED = 'int';
    const TYPE_LOCALIZED = 'loc';

    /** @var string|null */
    public $name;

    /** @var string */
    public $city;

    /** @var string */
    public $countryCode;

    /** @var string|null */
    public $organization;

    /** @var string */
    public $streetLine1;

    /** @var string|null */
    public $streetLine2;

    /** @var string|null */
    public $streetLine3;

    /** @var string|null */
    public $stateOrProvince;

    /** @var string|null */
    public $postalCode;

    /** @var string */
    public $type;

    public function __construct(
        ?string $name,
        string $city,
        string $countryCode,
        ?string $organization,
        string $streetLine1,
        ?string $streetLine2 = null,
        ?string $streetLine3 = null,
        ?string $stateOrProvince = null,
        ?string $postalCode = null,
        string $type = ContactPostalInfo::TYPE_INTERNATIONALIZED
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->city = $city;
        $this->countryCode = $countryCode;
        $this->organization = $organization;
        $this->streetLine1 = $streetLine1;
        $this->streetLine2 = $streetLine2;
        $this->streetLine3 = $streetLine3;
        $this->stateOrProvince = $stateOrProvince;
        $this->postalCode = $postalCode;

        $types = [ContactPostalInfo::TYPE_INTERNATIONALIZED, ContactPostalInfo::TYPE_LOCALIZED];
        Assert::inArray($type, $types, sprintf('Type must be one of (%s)', implode(', ', $types)));
        $this->type = $type;
    }

    public static function fromXML(DOMElement $domainCheckData): ContactPostalInfo
    {
        if ($domainCheckData->localName !== 'postalInfo') {
            throw new EppXmlException('DomainCheckData can only be parsed from a <contact:postalInfo/> element.');
        }

        // Required data
        $cityTag        = $domainCheckData->getElementsByTagName('city')->item(0);
        $countryCodeTag = $domainCheckData->getElementsByTagName('cc')->item(0);
        $streetLine1    = $domainCheckData->getElementsByTagName('street')->item(0);

        if (! $cityTag instanceof DOMElement) {
            throw new InvalidArgumentException('<contact:city /> is required.');
        }
        if (! $countryCodeTag instanceof DOMElement) {
            throw new InvalidArgumentException('<contact:cc /> is required.');
        }
        if (! $streetLine1 instanceof DOMElement) {
            throw new InvalidArgumentException('At least one <contact:street /> is required.');
        }

        // Optional data
        $nameTag         = $domainCheckData->getElementsByTagName('name')->item(0);
        $organization    = $domainCheckData->getElementsByTagName('org')->item(0);
        $streetLine2     = $domainCheckData->getElementsByTagName('street')->item(1);
        $streetLine3     = $domainCheckData->getElementsByTagName('street')->item(2);
        $stateOrProvince = $domainCheckData->getElementsByTagName('sp')->item(0);
        $postalCode      = $domainCheckData->getElementsByTagName('pc')->item(0);
        $type            = $domainCheckData->getAttribute('type');

        return new ContactPostalInfo(
            $nameTag !== null ? trim($nameTag->textContent) : null,
            trim($cityTag->textContent),
            trim($countryCodeTag->textContent),
            $organization !== null ? trim($organization->textContent) : null,
            trim($streetLine1->textContent),
            $streetLine2 !== null ? trim($streetLine2->textContent) : null,
            $streetLine3 !== null ? trim($streetLine3->textContent) : null,
            $stateOrProvince !== null ? trim($stateOrProvince->textContent) : null,
            $postalCode !== null ? trim($postalCode->textContent) : null,
            $type
        );
    }

    public function toXML(?string $overrideType = null): DOMElement
    {
        if ($overrideType) {
            $types = [ContactPostalInfo::TYPE_INTERNATIONALIZED, ContactPostalInfo::TYPE_LOCALIZED];
            Assert::inArray($overrideType, $types, sprintf('Type must be one of (%s)', implode(', ', $types)));
        }

        return ContactPostalInfoElement::render([

            $this->name !== null ? ContactName::render([], $this->name) : null,
            $this->organization !== null ? ContactOrganization::render([], $this->organization) : null,

            ContactAddress::render([
                ContactStreet::render([], $this->streetLine1),
                $this->streetLine2 !== null ? ContactStreet::render([], $this->streetLine2) : null,
                $this->streetLine3 !== null ? ContactStreet::render([], $this->streetLine3) : null,
                ContactCity::render([], $this->city),
                $this->stateOrProvince !== null ? ContactStateOrProvince::render([], $this->stateOrProvince) : null,
                $this->postalCode !== null ? ContactPostalCode::render([], $this->postalCode) : null,
                ContactCountryCode::render([], $this->countryCode),
            ]),

        ], null, ['type' => $overrideType ?? $this->type]);
    }
}
