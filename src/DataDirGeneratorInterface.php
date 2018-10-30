<?php

declare(strict_types=1);

namespace BitWasp\Groestlcoind;

interface DataDirGeneratorInterface
{
    public function createNextDir(): string;
    public function getDirs(): array;
}
