<?php

declare(strict_types=1);

namespace BitWasp\Test\Util;

use BitWasp\Groestlcoind\Exception\GroestlcoindException;
use BitWasp\Groestlcoind\Utils\File;
use BitWasp\Test\Groestlcoind\TestCase;

class FileTest extends TestCase
{
    public function testRequiresDirectory()
    {
        $file = $this->registerTmpFile("tmpfile-requires-directory");
        file_put_contents($file, "");

        $this->expectException(GroestlcoindException::class);
        $this->expectExceptionMessage("Parameter 1 for recursiveDelete should be a directory");
        File::recursiveDelete($file);
    }
}
