<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\Rfc\Responses;

use SandwaveIo\EppClient\Epp\Rfc\Document;

abstract class Response
{
    /** @var Document */
    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function getResultCode(): string
    {
//        TODO: Implement getter.
        return 'tmp';
    }
}
