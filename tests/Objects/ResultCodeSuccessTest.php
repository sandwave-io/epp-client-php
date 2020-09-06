<?php


namespace SandwaveIo\EppClient\Tests\Objects;


use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ResultCode;

class ResultCodeSuccessTest extends TestCase
{
    public function successData(): array
    {
        return [
            ['1000', true],
            ['1001', true],
            ['1300', true],
            ['1301', true],
            ['1500', true],
            ['2000', false],
            ['2001', false],
            ['2002', false],
            ['2003', false],
            ['2004', false],
            ['2005', false],
            ['2100', false],
            ['2101', false],
            ['2102', false],
            ['2103', false],
            ['2104', false],
            ['2105', false],
            ['2106', false],
            ['2200', false],
            ['2201', false],
            ['2202', false],
            ['2300', false],
            ['2301', false],
            ['2302', false],
            ['2303', false],
            ['2304', false],
            ['2305', false],
            ['2306', false],
            ['2307', false],
            ['2308', false],
            ['2400', false],
            ['2500', false],
            ['2501', false],
            ['2502', false],
        ];
    }

    /** @dataProvider successData */
    public function test_is_success(string $code, bool $isSuccess): void
    {
        $code = ResultCode::fromString($code);

        $this->assertSame($isSuccess, $code->isSuccess(), "Failed asserting that {$code} has status: ".($isSuccess ? 'success' : 'failure'));
        $this->assertSame($isSuccess, ! $code->isFailure(), "Failed asserting that {$code} has status: ".($isSuccess ? 'success' : 'failure'));
    }
}