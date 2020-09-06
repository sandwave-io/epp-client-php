<?php


namespace SandwaveIo\EppClient\Epp\Rfc\Responses\Objects;


use SandwaveIo\EppClient\Exceptions\UnexpectedValueException;

class ResultCode
{
    /** @var string */
    private $code;

    private function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function isValid(string $code): bool
    {
        return (bool) preg_match('/^[1-2][0-5][0-9]{2}$/', $code);
    }

    public static function fromString(string $code): ResultCode
    {
        if (! ResultCode::isValid($code)) {
            throw new UnexpectedValueException("Result code {$code} does not comply with RFC 5730.");
        }
        return new ResultCode($code);
    }

    public function toString(): string
    {
        return $this->code;
    }

    public function isSuccess(): bool
    {
        return (bool) preg_match('/^[1][0-5][0-9]{2}$/', $this->code);
    }

    public function isFailure(): bool
    {
        return ! $this->isSuccess();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}