<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Contact;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Transfer;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactAuthInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactId;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactTransfer;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class ContactRequestTransferRequest extends Request
{
    /** @var string */
    private $contact;

    /** @var string */
    private $password;

    public function __construct(string $contact, string $password)
    {
        parent::__construct();

        $this->contact = $contact;
        $this->password = $password;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Transfer::render([
                    ContactTransfer::render([
                        ContactId::render([], $this->contact),

                        ContactAuthInfo::render([
                            ContactPassword::render([], $this->password),
                        ]),

                    ]),
                ], null, ['op' => 'request']),
                $this->renderExtension(),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }
}
