<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Contact;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Delete;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactDelete;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Contact\ContactId;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class ContactDeleteRequest extends Request
{
    /** @var string */
    private $contact;

    public function __construct(string $contact)
    {
        parent::__construct();

        $this->contact = $contact;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Delete::render([
                    ContactDelete::render([
                        ContactId::render([], $this->contact),
                    ]),
                ]),
                $this->renderExtension(),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }
}
