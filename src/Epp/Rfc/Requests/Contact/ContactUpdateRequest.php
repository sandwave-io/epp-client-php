<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Contact;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Update;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\Address\ContactAddress;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactAdd;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactAuthInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactChg;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactDisclosure;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactEmail;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactFax;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactId;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactOrganization;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactRem;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactStatus;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactUpdate;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactVoice;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects;
use SandwaveIo\EppClient\Exceptions\InvalidArgumentException;
use Webmozart\Assert\Assert;

class ContactUpdateRequest extends Request
{
    const DISCLOSE_NAME_INT = '<contact:name type="int"/>';
    const DISCLOSE_NAME_LOC = '<contact:name type="loc"/>';
    const DISCLOSE_ORG_INT  = '<contact:org type="int"/>';
    const DISCLOSE_ORG_LOC  = '<contact:org type="loc"/>';
    const DISCLOSE_ADDR_INT = '<contact:addr type="int"/>';
    const DISCLOSE_ADDR_LOC = '<contact:addr type="loc"/>';
    const DISCLOSE_VOICE    = '<contact:voice/>';
    const DISCLOSE_FAX      = '<contact:fax/>';
    const DISCLOSE_EMAIL    = '<contact:email/>';

    /** @var string */
    private $contact;

    /** @var array|null */
    private $addStatuses;

    /** @var array|null */
    private $removeStatuses;

    /** @var string|null */
    private $password;

    /** @var Objects\ContactPostalInfo|null */
    private $internationalAddress;

    /** @var Objects\ContactPostalInfo|null */
    private $localAddress;

    /** @var string|null */
    private $voice;

    /** @var string|null */
    private $fax;

    /** @var array|null */
    private $disclosure;

    /** @var bool|null */
    private $doDisclose;

    public function __construct(
        string $contact,
        ?array $addStatuses = null,
        ?array $removeStatuses = null,
        ?string $updatePassword = null,
        ?Objects\ContactPostalInfo $updateInternationalAddress = null,
        ?Objects\ContactPostalInfo $updateLocalAddress = null,
        ?string $updateVoice = null,
        ?string $updateFax = null,
        ?array $updateDisclosure = null,
        ?bool $updateDoDisclose = null
    ) {
        parent::__construct();

        $this->contact = $contact;
        $this->addStatuses = $addStatuses;
        $this->removeStatuses = $removeStatuses;
        $this->password = $updatePassword;
        $this->internationalAddress = $updateInternationalAddress;
        $this->localAddress = $updateLocalAddress;
        $this->voice = $updateVoice;
        $this->fax = $updateFax;
        $this->disclosure = $updateDisclosure;
        $this->doDisclose = $updateDoDisclose;

        if (is_null($updateInternationalAddress) && is_null($updateLocalAddress) && ($this->password || $this->internationalAddress || $this->localAddress || $this->voice || $this->fax)) {
            throw new InvalidArgumentException('At leas one of $updateInternationalAddress and $updateLocalAddress must be set.');
        }
        if (! is_null($updateDisclosure) && is_null($updateDoDisclose)) {
            throw new InvalidArgumentException('When disclosing elements, $updateDoDisclose MUST be set.');
        }
        if ($updateDisclosure) {
            $updateDisclosureElements = [
                ContactUpdateRequest::DISCLOSE_NAME_INT,
                ContactUpdateRequest::DISCLOSE_NAME_LOC,
                ContactUpdateRequest::DISCLOSE_ORG_INT,
                ContactUpdateRequest::DISCLOSE_ORG_LOC,
                ContactUpdateRequest::DISCLOSE_ADDR_INT,
                ContactUpdateRequest::DISCLOSE_ADDR_LOC,
                ContactUpdateRequest::DISCLOSE_VOICE,
                ContactUpdateRequest::DISCLOSE_FAX,
                ContactUpdateRequest::DISCLOSE_EMAIL,
            ];
            foreach ($updateDisclosure as $disclose) {
                Assert::inArray(
                    $disclose,
                    $updateDisclosureElements,
                    sprintf('Disclosed elements must be one of (%s)', implode(', ', $updateDisclosureElements))
                );
            }
        }
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Update::render([
                    ContactUpdate::render([
                        ContactId::render([], $this->contact),

                        $this->addStatuses
                            ? ContactAdd::render(array_map(function (string $status) {
                                return ContactStatus::render([], null, ['s' => $status]);
                            }, $this->addStatuses))
                            : null,

                        $this->removeStatuses
                            ? ContactRem::render(array_map(function (string $status) {
                                return ContactStatus::render([], null, ['s' => $status]);
                            }, $this->removeStatuses))
                            : null,

                        ($this->password || $this->internationalAddress || $this->localAddress || $this->voice || $this->fax || $this->disclosure)
                            ? ContactChg::render([
                                    $this->internationalAddress !== null
                                        ? $this->internationalAddress->toXML(Objects\ContactPostalInfo::TYPE_INTERNATIONALIZED)
                                        : null,

                                    $this->localAddress !== null
                                        ? $this->localAddress->toXML(Objects\ContactPostalInfo::TYPE_LOCALIZED)
                                        : null,

                                    $this->voice !== null
                                        ? ContactVoice::render([], $this->voice)
                                        : null,

                                    $this->fax !== null
                                        ? ContactFax::render([], $this->fax)
                                        : null,

                                    $this->password !== null
                                        ? ContactAuthInfo::render([
                                            ContactPassword::render([], $this->password),
                                        ])
                                        : null,

                                    $this->disclosure !== null
                                        ? ContactDisclosure::render(
                                            array_map(function (string $disclosure) {
                                                return $this->renderDisclosureElement($disclosure);
                                            }, $this->disclosure),
                                            null,
                                            ['flag' => $this->doDisclose ? '1' : '0']
                                        )
                                        : null,
                            ])
                            : null,
                    ]),
                ]),
                $this->renderExtension(),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }

    private function renderDisclosureElement(string $disclosure): DOMElement
    {
        switch ($disclosure) {

            case ContactUpdateRequest::DISCLOSE_NAME_INT:
                return ContactName::render([], null, ['type' => 'int']);

            case ContactUpdateRequest::DISCLOSE_NAME_LOC:
                return ContactName::render([], null, ['type' => 'loc']);

            case ContactUpdateRequest::DISCLOSE_ORG_INT:
                return ContactOrganization::render([], null, ['type' => 'int']);

            case ContactUpdateRequest::DISCLOSE_ORG_LOC:
                return ContactOrganization::render([], null, ['type' => 'loc']);

            case ContactUpdateRequest::DISCLOSE_ADDR_INT:
                return ContactAddress::render([], null, ['type' => 'int']);

            case ContactUpdateRequest::DISCLOSE_ADDR_LOC:
                return ContactAddress::render([], null, ['type' => 'loc']);

            case ContactUpdateRequest::DISCLOSE_VOICE:
                return ContactVoice::render();

            case ContactUpdateRequest::DISCLOSE_FAX:
                return ContactFax::render();

            case ContactUpdateRequest::DISCLOSE_EMAIL:
                return ContactEmail::render();
        }

        throw new InvalidArgumentException('Given disclosure element does not exist.');
    }
}
