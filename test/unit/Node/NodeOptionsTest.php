<?php

declare(strict_types=1);

namespace BitWasp\Test\Groestlcoind\Node;

use BitWasp\Groestlcoind\Config\Config;
use BitWasp\Groestlcoind\Node\NodeOptions;
use BitWasp\Test\Groestlcoind\TestCase;

class NodeOptionsTest extends TestCase
{
    public function testBasicOptions()
    {
        $groestlcoindPath = "/usr/bin/groestlcoind";
        $dataDir = "/data/";
        $options = new NodeOptions(
            $groestlcoindPath,
            $dataDir
        );

        $this->assertEquals($groestlcoindPath, $options->getGroestlcoindPath());
        $this->assertEquals($dataDir, $options->getDataDir());
        $this->assertEquals("groestlcoin.conf", $options->getConfigFileName());
        $this->assertEquals("{$dataDir}groestlcoin.conf", $options->getAbsoluteConfigPath());
        $this->assertEquals("{$groestlcoindPath} -datadir={$dataDir}", $options->getStartupCommand());
        $this->assertFalse($options->hasConfig());
    }

    public function testDefaultConfigFile()
    {
        $groestlcoindPath = "/usr/bin/groestlcoind";
        $dataDir = "/data";
        $options = new NodeOptions(
            $groestlcoindPath,
            $dataDir
        );

        $this->assertEquals("groestlcoin.conf", $options->getConfigFileName());
        $this->assertEquals("{$dataDir}/", $options->getDataDir());
    }

    public function testOverrideConfigFile()
    {
        $groestlcoindPath = "/usr/bin/groestlcoind";
        $dataDir = "/data";
        $options = new NodeOptions(
            $groestlcoindPath,
            $dataDir
        );
        $options->withConfigFileName("bitcoin.conf");

        $this->assertEquals("{$dataDir}/", $options->getDataDir());
        $this->assertEquals("bitcoin.conf", $options->getConfigFileName());
        $this->assertEquals("{$dataDir}/bitcoin.conf", $options->getAbsoluteConfigPath());
    }

    public function testHasConfig()
    {
        $groestlcoindPath = "/usr/bin/groestlcoind";
        $datadir = sys_get_temp_dir()."/testdir/";
        $configpath = $datadir."groestlcoin.conf";
        @mkdir($datadir);

        file_put_contents($configpath, "");

        $options = new NodeOptions(
            $groestlcoindPath,
            $datadir
        );
        $this->assertTrue($options->hasConfig());
        unlink($configpath);
    }

    public function testGetPidPath()
    {
        $groestlcoindPath = "/usr/bin/groestlcoind";
        $datadir = sys_get_temp_dir()."/testdir/";
        $configpath = $datadir."groestlcoin.conf";
        @mkdir($datadir);

        file_put_contents($configpath, "");

        $options = new NodeOptions(
            $groestlcoindPath,
            $datadir
        );

        $mainnetConfig = new Config();
        $this->assertEquals("{$datadir}groestlcoind.pid", $options->getAbsolutePidPath($mainnetConfig));

        $testnetConfig = new Config([
            'testnet' => 1
        ]);
        $this->assertEquals("{$datadir}testnet3/groestlcoind.pid", $options->getAbsolutePidPath($testnetConfig));

        $regtestConfig = new Config([
            'regtest' => 1
        ]);
        $this->assertEquals("{$datadir}regtest/groestlcoind.pid", $options->getAbsolutePidPath($regtestConfig));
        unlink($configpath);
    }
}
