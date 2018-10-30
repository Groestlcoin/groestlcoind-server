<?php

declare(strict_types=1);

namespace BitWasp\Groestlcoind\Config;

use BitWasp\Groestlcoind\Exception\GroestlcoindException;

class FilesystemWriter extends Writer
{
    public function save(string $filePath, Config $config)
    {
        $file = "";
        foreach ($config->all() as $key => $option) {
            $file .= "{$key}={$option}\n";
        }
        if (!file_put_contents($filePath, $file)) {
            throw new GroestlcoindException("Failed to write config file");
        }
    }

    public function create(string $filePath, Config $config)
    {
        if (file_exists($filePath)) {
            throw new GroestlcoindException("Cannot overwrite existing files with FilesystemWriter::create");
        }

        return $this->save($filePath, $config);
    }
}
