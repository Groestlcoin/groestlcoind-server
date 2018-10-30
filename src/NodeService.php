<?php

declare(strict_types=1);

namespace BitWasp\Groestlcoind;

use BitWasp\Groestlcoind\Config;
use BitWasp\Groestlcoind\Node\NodeOptions;
use BitWasp\Groestlcoind\Node\Server;

class NodeService
{
    protected function checkGroestlcoindExists(string $groestlcoind)
    {
        if (!file_exists($groestlcoind)) {
            throw new Exception\SetupException("Path to groestlcoind executable is invalid: {$groestlcoind}");
        }

        if (!is_executable($groestlcoind)) {
            throw new Exception\SetupException("Groestlcoind must be executable");
        }
    }

    protected function setupDataDir(NodeOptions $options, Config\Config $config, Config\Writer $writer)
    {
        if (is_dir($options->getDataDir())) {
            throw new Exception\SetupException("Cannot create a node in non-empty datadir");
        }

        if (!mkdir($options->getDataDir())) {
            throw new Exception\SetupException("Could not create datadir ({$options->getDataDir()}) - is it writable?");
        }

        $writer->create($options->getAbsoluteConfigPath(), $config);
    }

    public function createNewNode(NodeOptions $options, Config\Config $config, Config\Writer $writer): Server
    {
        $this->checkGroestlcoindExists($options->getGroestlcoindPath());
        $this->setupDataDir($options, $config, $writer);

        return new Server($options);
    }

    public function loadNode(NodeOptions $options): Server
    {
        $this->checkGroestlcoindExists($options->getGroestlcoindPath());
        return new Server($options);
    }
}
