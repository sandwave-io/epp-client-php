<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifierElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\CommandElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\EppElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\ClientIdElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\LangElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\LoginElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\NewPasswordElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\OptionsElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\PasswordElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\VersionElement;

final class LoginRequest extends Request
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string|null */
    private $newPassword;

    public function __construct(string $username, string $password, ?string $newPassword = null)
    {
        parent::__construct();
        $this->username = $username;
        $this->password = $password;
        $this->newPassword = $newPassword;
    }

    protected function renderElements(): DOMElement
    {
        return EppElement::render([
            CommandElement::render([
                LoginElement::render([

                    ClientIdElement::render([], $this->username),
                    PasswordElement::render([], $this->password),
                    $this->newPassword ? NewPasswordElement::render([], $this->newPassword) : null,

                    OptionsElement::render([
                        VersionElement::render([], '1.0'),
                        LangElement::render([], 'en'),
                    ]),

                ]),
                ClientTransactionIdentifierElement::render([], 'ABCD-1234'),
            ]),
        ]);
    }
}
