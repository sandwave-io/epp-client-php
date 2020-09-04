<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Services;

use SandwaveIo\EppClient\Epp\Connection;
use SandwaveIo\EppClient\Epp\Rfc\Requests\LoginRequest;
use SandwaveIo\EppClient\Epp\Rfc\Responses\LoginResponse;

abstract class AbstractService
{
    /** @var Connection */
    protected $connection;

    /** @var array<string> */
    protected $connections = [];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function login(string $username, string $password): LoginResponse
    {
        $request = new LoginRequest($username, $password);
        return new LoginResponse($this->connection->execute($request));
    }

    public function logout(string $username, string $password): LoginResponse
    {
        $request = new LoginRequest($username, $password);
        return new LoginResponse($this->connection->execute($request));
    }
}
