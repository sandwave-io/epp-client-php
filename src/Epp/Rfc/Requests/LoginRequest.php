<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Requests;

use DOMElement;
use SandwaveIo\EppClient\Epp\Rfc\Elements\ClientTransactionIdentifier;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Command;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\ClientId;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\ExtensionURI;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\Lang;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\Login;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\NewPassword;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\ObjectURI;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\Options;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\Password;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\ServiceExtension;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\Services;
use SandwaveIo\EppClient\Epp\Rfc\Elements\Login\Version;

class LoginRequest extends Request
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string|null */
    private $newPassword;

    /** @var array<string> */
    private $serviceExtensions;

    public function __construct(string $username, string $password, ?string $newPassword = null, array $extensions = [], array $serviceExtensions = [], ?string $clientTransactionIdentifier = null)
    {
        parent::__construct($clientTransactionIdentifier, $extensions);

        $this->username = $username;
        $this->password = $password;
        $this->newPassword = $newPassword;
        $this->serviceExtensions = $serviceExtensions;
    }

    protected function renderElements(): DOMElement
    {
        return $this->renderEppElement([
            Command::render([
                Login::render([

                    ClientId::render([], $this->username),
                    Password::render([], $this->password),
                    $this->newPassword ? NewPassword::render([], $this->newPassword) : null,

                    Options::render([
                        Version::render([], '1.0'),
                        Lang::render([], 'en'),
                    ]),

                    Services::render([
                        ObjectURI::render([], 'urn:ietf:params:xml:ns:domain-1.0'),
                        ObjectURI::render([], 'urn:ietf:params:xml:ns:contact-1.0'),
                        ObjectURI::render([], 'urn:ietf:params:xml:ns:host-1.0'),

                        $this->renderExtensions(),
                    ]),

                ]),
                $this->clientTransactionIdentifier ? ClientTransactionIdentifier::render([], $this->clientTransactionIdentifier) : null,
            ]),
        ]);
    }

    private function renderExtensions(): ?DOMElement
    {
        if (count($this->serviceExtensions) === 0) {
            return null;
        }

        $extensionURIs = array_map(function ($uri) {
            return ExtensionURI::render([], $uri);
        }, $this->serviceExtensions);

        return ServiceExtension::render($extensionURIs);
    }
}
