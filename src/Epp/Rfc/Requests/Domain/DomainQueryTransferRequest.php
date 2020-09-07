<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Domain;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainAuthInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainTransfer;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Transfer;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class DomainQueryTransferRequest extends Request
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
                ], null, ['op' => 'query']),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }
}
