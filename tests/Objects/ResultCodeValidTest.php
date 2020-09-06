<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests\Objects;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Responses\Objects\ResultCode;

class ResultCodeValidTest extends TestCase
{
    public function validData(): array
    {
        return [
            ['1000', true],
            ['1001', true],
            ['1300', true],
            ['1301', true],
            ['1500', true],
            ['2000', true],
            ['2001', true],
            ['2002', true],
            ['2003', true],
            ['2004', true],
            ['2005', true],
            ['2100', true],
            ['2101', true],
            ['2102', true],
            ['2103', true],
            ['2104', true],
            ['2105', true],
            ['2106', true],
            ['2200', true],
            ['2201', true],
            ['2202', true],
            ['2300', true],
            ['2301', true],
            ['2302', true],
            ['2303', true],
            ['2304', true],
            ['2305', true],
            ['2306', true],
            ['2307', true],
            ['2308', true],
            ['2400', true],
            ['2500', true],
            ['2501', true],
            ['2502', true],
            ['25021', false],
            ['2600', false],
            ['3000', false],
        ];
    }

    /** @dataProvider validData */
    public function test_is_valid($code, $isValid): void
    {
        $this->assertSame($isValid, ResultCode::isValid($code), "Failed asserting that result code {$code} is " . ($isValid ? 'valid.' : 'invalid.'));
    }
}
