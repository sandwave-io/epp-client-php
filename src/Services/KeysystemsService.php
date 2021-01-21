<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Services;

use SandwaveIo\EppClient\Epp\Rfc\Document;
use SandwaveIo\EppClient\Epp\Rfc\Requests\Request;

final class KeysystemsService extends AbstractService
{
    protected function request(Request $request, ?string $transactionId = null): Document
    {
        $request->addEppExtension('keysys', 'http://www.key-systems.net/epp/keysys-1.0');
        return parent::request($request, $transactionId);
    }
}
