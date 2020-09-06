<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainTransfer\DomainAuthInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainTransfer\DomainName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainTransfer\DomainPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainTransfer\DomainTransfer;
use SandwaveIo\EppClient\Epp\Rfc\Elements\DomainTransfer\Transfer;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;

class DomainTransferRequest extends Request
{
    /** @var string */
    private $domain;

    /** @var string|null */
    private $password;

    /** @var string|null */
    private $registrantObjectId;

    public function __construct(string $domain, ?string $password = null, ?string $registrantObjectId = null)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->password = $password;
        $this->registrantObjectId = $registrantObjectId;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Transfer::render([
                    DomainTransfer::render([
                        DomainName::render([], $this->domain),

                        $this->password
                            ? DomainAuthInfo::render([
                                DomainPassword::render(
                                    [],
                                    $this->password,
                                    $this->registrantObjectId ? ['roid' => $this->registrantObjectId] : []
                                ),
                            ])
                            : null,

                    ]),
                ]),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }
}
