<?php

declare(strict_types=1);

namespace BitWasp\Groestlcoind\Node;

use BitWasp\Groestlcoind\Config\Config;

class NodeOptions
{
    /**
     * @var string
     */
    private $configFileName = "groestlcoin.conf";

    /**
     * @var string
     */
    private $dataDir;

    /**
     * @var string
     */
    private $groestlcoind;

    /**
     * NodeOptions constructor.
     * @param string $groestlcoind - path to groestlcoind executable
     * @param string $dataDir - path to groestlcoin datadir
     */
    public function __construct(string $groestlcoind, string $dataDir)
    {
        if (substr($dataDir, -1) !== "/") {
            $dataDir = "{$dataDir}/";
        }

        $this->groestlcoind = $groestlcoind;
        $this->dataDir = $dataDir;
    }

    public function withConfigFileName(string $fileName): NodeOptions
    {
        $this->configFileName = $fileName;
        return $this;
    }

    public function getGroestlcoindPath(): string
    {
        return $this->groestlcoind;
    }

    public function getDataDir(): string
    {
        return $this->dataDir;
    }

    public function getConfigFileName(): string
    {
        return $this->configFileName;
    }

    private function getAbsolutePath(string $path): string
    {
        return "{$this->dataDir}{$path}";
    }

    public function getAbsoluteConfigPath(): string
    {
        return $this->getAbsolutePath($this->configFileName);
    }

    public function getAbsolutePidPath(Config $config): string
    {
        return $this->getAbsolutePath($config->getRelativePidPath());
    }

    public function getAbsoluteLogPath(Config $config): string
    {
        return $this->getAbsolutePath($config->getRelativeLogPath());
    }

    public function getStartupCommand(): string
    {
        return sprintf("%s -datadir=%s", $this->getGroestlcoindPath(), $this->getDataDir());
    }

    public function hasConfig(): bool
    {
        $configPath = $this->getAbsoluteConfigPath();
        return file_exists($configPath) && is_file($configPath);
    }
}
