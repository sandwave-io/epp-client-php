<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests\Domain;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Commands\Transfer;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainAuthInfo;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainName;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainPeriod;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Domain\DomainTransfer;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Epp;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

class DomainRequestTransferRequest extends Request
{
    /** @var string */
    private $domain;

    /** @var string */
    private $password;

    /** @var string|null */
    private $roid;

    /** @var int|null */
    private $period;

    /**
     * DomainRequestTransferRequest constructor.
     *
     * @param string      $domain
     * @param string      $password
     * @param string|null $repositoryObjectId ID of contact or registrant if the password belongs to either such an entity.
     * @param int|null    $period
     */
    public function __construct(string $domain, string $password, ?string $repositoryObjectId = null, ?int $period = null)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->password = $password;
        $this->roid = $repositoryObjectId;
        $this->period = $period;
    }

    protected function renderElements(): DOMElement
    {
        return Epp::render([
            Command::render([
                Transfer::render([
                    DomainTransfer::render([

                        DomainName::render([], $this->domain),

                        $this->period ? DomainPeriod::render([], (string) $this->period, ['unit' => 'y']) : null,

                        DomainAuthInfo::render([
                            DomainPassword::render(
                                [],
                                $this->password,
                                $this->roid ? ['roid' => $this->roid] : []
                            ),
                        ]),
                    ]),
                ], null, ['op' => 'request']),

                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ], null, $this->extensions);
    }
}
