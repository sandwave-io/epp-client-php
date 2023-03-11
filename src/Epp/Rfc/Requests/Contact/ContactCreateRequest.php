<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Contact;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Create;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\Address\ContactAddress;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactAuthInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactCreate;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactDisclosure;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactEmail;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactFax;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactId;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactOrganization;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactVoice;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects;
use SandwaveIo\EppClient\Exceptions\InvalidArgumentException;

class ContactCreateRequest extends Request
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

    /** @var Objects\ContactPostalInfo|null */
    protected $internationalAddress;

    /** @var Objects\ContactPostalInfo|null */
    protected $localAddress;

    /** @var string */
    private $contact;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

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
        string $email,
        string $password,
        ?Objects\ContactPostalInfo $internationalAddress = null,
        ?Objects\ContactPostalInfo $localAddress = null,
        ?string $voice = null,
        ?string $fax = null,
        ?array $disclosure = null,
        ?bool $doDisclose = null
    ) {
        parent::__construct();

        $this->contact = $contact;
        $this->email = $email;
        $this->password = $password;
        $this->internationalAddress = $internationalAddress;
        $this->localAddress = $localAddress;
        $this->voice = $voice;
        $this->fax = $fax;
        $this->disclosure = $disclosure;
        $this->doDisclose = $doDisclose;

        if (is_null($internationalAddress) && is_null($localAddress)) {
            throw new InvalidArgumentException('At leas one of $internationalAddress and $localAddress must be set.');
        }
        if (! is_null($disclosure) && is_null($doDisclose)) {
            throw new InvalidArgumentException('When disclosing elements, $doDisclose MUST be set.');
        }
        if ($disclosure) {
            $disclosureElements = [
                ContactCreateRequest::DISCLOSE_NAME_INT,
                ContactCreateRequest::DISCLOSE_NAME_LOC,
                ContactCreateRequest::DISCLOSE_ORG_INT,
                ContactCreateRequest::DISCLOSE_ORG_LOC,
                ContactCreateRequest::DISCLOSE_ADDR_INT,
                ContactCreateRequest::DISCLOSE_ADDR_LOC,
                ContactCreateRequest::DISCLOSE_VOICE,
                ContactCreateRequest::DISCLOSE_FAX,
                ContactCreateRequest::DISCLOSE_EMAIL,
            ];
            foreach ($disclosure as $disclose) {
                assert(in_array(
                    $disclose,
                    $disclosureElements,
                    true
                ), sprintf('Disclosed elements must be one of (%s)', implode(', ', $disclosureElements)));
            }
        }
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Create::render([
                    ContactCreate::render([
                        ContactId::render([], $this->contact),

                        $this->internationalAddress
                            ? $this->internationalAddress->toXML(Objects\ContactPostalInfo::TYPE_INTERNATIONALIZED)
                            : null,

                        $this->localAddress
                            ? $this->localAddress->toXML(Objects\ContactPostalInfo::TYPE_LOCALIZED)
                            : null,

                        $this->voice
                            ? ContactVoice::render([], $this->voice)
                            : null,

                        $this->fax
                            ? ContactFax::render([], $this->fax)
                            : null,

                        ContactEmail::render([], $this->email),

                        ContactAuthInfo::render([
                            ContactPassword::render([], $this->password),
                        ]),

                        $this->disclosure
                            ? ContactDisclosure::render(
                                array_map(function (string $disclosure) {
                                    return $this->renderDisclosureElement($disclosure);
                                }, $this->disclosure),
                                null,
                                ['flag' => $this->doDisclose ? '1' : '0']
                            )
                            : null,
                    ]),
                ]),
                $this->renderExtension(),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->namespaces);
    }

    private function renderDisclosureElement(string $disclosure): DOMElement
    {
        switch ($disclosure) {
            case ContactCreateRequest::DISCLOSE_NAME_INT:
                return ContactName::render([], null, ['type' => 'int']);

            case ContactCreateRequest::DISCLOSE_NAME_LOC:
                return ContactName::render([], null, ['type' => 'loc']);

            case ContactCreateRequest::DISCLOSE_ORG_INT:
                return ContactOrganization::render([], null, ['type' => 'int']);

            case ContactCreateRequest::DISCLOSE_ORG_LOC:
                return ContactOrganization::render([], null, ['type' => 'loc']);

            case ContactCreateRequest::DISCLOSE_ADDR_INT:
                return ContactAddress::render([], null, ['type' => 'int']);

            case ContactCreateRequest::DISCLOSE_ADDR_LOC:
                return ContactAddress::render([], null, ['type' => 'loc']);

            case ContactCreateRequest::DISCLOSE_VOICE:
                return ContactVoice::render();

            case ContactCreateRequest::DISCLOSE_FAX:
                return ContactFax::render();

            case ContactCreateRequest::DISCLOSE_EMAIL:
                return ContactEmail::render();
        }

        throw new InvalidArgumentException('Given disclosure element does not exist.');
    }
}
