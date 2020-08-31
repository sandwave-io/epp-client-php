<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Tests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LoginRequest;

class ExampleTest extends TestCase
{
    public function test_debug(): void
    {
        $request = new LoginRequest('admin', 'secret');
        echo $request->renderAndAppendChildren()->toString();
    }
}
