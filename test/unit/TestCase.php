<?php

namespace BitWasp\Test\Groestlcoind;

use BitWasp\Groestlcoind\Utils\File;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    private $groestlcoind;

    /**
     * @var string[]
     */
    private $files = [];

    /**
     * @var string[]
     */
    private $dirs = [];

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        if (getenv('TRAVIS_BUILD_DIR')) {
            $this->groestlcoind = getenv('HOME') . "/groestlcoin/groestlcoin-" . getenv("GROESTLCOIN_VERSION") . "/bin/groestlcoind";
        } else {
            if (!getenv('GROESTLCOIND_PATH')) {
                throw new \RuntimeException("Missing GROESTLCOIND_PATH environment variable");
            }

            if (!is_executable(getenv('GROESTLCOIND_PATH'))) {
                throw new \RuntimeException("GROESTLCOIND_PATH environment vairable should be an executable");
            }

            $this->groestlcoind = getenv('GROESTLCOIND_PATH');
        }

        parent::__construct($name, $data, $dataName);
    }

    public function __destruct()
    {
        foreach ($this->files as $file) {
            if (is_file($file)) {
                if (getenv('DEBUG_DELETE')) {
                    echo "[cleanup] file $file ... \n";
                }

                unlink($file);
            }
        }

        foreach ($this->dirs as $dir) {
            if (is_dir($dir)) {
                if (getenv('DEBUG_DELETE')) {
                    echo "[cleanup] dir $dir ... \n";
                }
                File::recursiveDelete($dir);
            }
        }
    }

    protected function registerTmpFile(string $path): string
    {
        if (file_exists($path)) {
            if (getenv('DEBUG_DELETE')) {
                echo "requested file which exists, delete file $path ... \n";
            }
            unlink($path);
        }

        $fullPath = sys_get_temp_dir() . "/$path";
        $this->files[] = $fullPath;
        return $fullPath;
    }

    protected function registerTmpDir(string $dirName): string
    {
        $fullPath = sys_get_temp_dir() . "/{$dirName}";
        if (file_exists($fullPath)) {
            if (getenv('DEBUG_DELETE')) {
                echo "requested file which exists, delete dir $fullPath ... \n";
            }
            File::recursiveDelete($fullPath);
        }

        $this->dirs[] = $fullPath;
        return $fullPath;
    }

    public function getDataFilePath(string $path): string
    {
        return __DIR__ . "/../data/{$path}";
    }

    public function loadDataFile(string $path): string
    {
        $fullPath = $this->getDataFilePath($path);
        $contents = file_get_contents($fullPath);
        if (false === $contents) {
            throw new \Exception("Failed to read requested data file: {$fullPath}");
        }

        return $contents;
    }

    public function getGroestlcoindPath()
    {
        return $this->groestlcoind;
    }
}
