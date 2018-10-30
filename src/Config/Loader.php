<?php

declare(strict_types=1);

namespace BitWasp\Groestlcoind\Config;

abstract class Loader
{
    abstract public function load(string $filePath): Config;
}
