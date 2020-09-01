<?php


namespace SandwaveIo\EppClient\Epp\ConnectionDriver\Support;


use SandwaveIo\EppClient\Exceptions\ConnectException;

class WriteBuffer
{
    /**
     * @var resource
     */
    private $connection;

    /** @var string */
    private $content;

    /**
     * WriteBuffer constructor.
     * @param resource $connection
     * @param string $content
     */
    public function __construct($connection, string $content = '')
    {
        $this->connection = $connection;
        $this->content = $content;
    }

    public function write(): void
    {
        if (! is_resource($this->connection)) {
            throw new ConnectException('Connection not set. Aborting..');
        }

        $this->content = $this->addContentLengthBlock($this->content);

        fwrite($this->connection, $this->content);
    }

    private function addContentLengthBlock(string $content): string
    {
        $int = pack('N', intval(strlen($content) + 4));
        return $int . $content;
    }

}