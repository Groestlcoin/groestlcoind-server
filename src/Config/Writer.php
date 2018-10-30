<?php

declare(strict_types=1);

namespace BitWasp\Groestlcoind\Config;

abstract class Writer
{
    abstract public function create(string $path, Config $config);
    abstract public function save(string $path, Config $config);
}
