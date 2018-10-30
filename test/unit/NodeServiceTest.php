<?php

declare(strict_types=1);

namespace BitWasp\Test\Groestlcoind;

use BitWasp\Groestlcoind\Config\Config;
use BitWasp\Groestlcoind\Config\FilesystemWriter;
use BitWasp\Groestlcoind\Config\Writer;
use BitWasp\Groestlcoind\Exception\SetupException;
use BitWasp\Groestlcoind\Node\NodeOptions;
use BitWasp\Groestlcoind\Node\Server;
use BitWasp\Groestlcoind\NodeService;

class NodeServiceTest extends TestCase
{
    public function testCreateNewEnsureWritesToFile()
    {
        $groestlcoind = $this->getGroestlcoindPath();
        $dataDir = $this->registerTmpDir("datadir-createnew");

        $options = new NodeOptions($groestlcoind, $dataDir);
        $config = new Config([
            'daemon' => '1',
            'regtest' => '1',
            'server' => '1',
        ]);

        $mock = $this->getMockForAbstractClass(Writer::class);
        $mock->expects($this->once())
            ->method("create")
            ->with($options->getAbsoluteConfigPath(), $config);

        $service = new NodeService();
        $node = $service->createNewNode($options, $config, $mock);
        $this->assertInstanceOf(Server::class, $node);
    }

    public function testCreateNewWithFilesystem()
    {
        $groestlcoind = $this->getGroestlcoindPath();
        $dataDir = $this->registerTmpDir("datadir-createnew-real");

        $options = new NodeOptions($groestlcoind, $dataDir);
        $config = new Config([
            'daemon' => '1',
            'regtest' => '1',
            'server' => '1',
        ]);

        $mock = new FilesystemWriter();

        $service = new NodeService();
        $node = $service->createNewNode($options, $config, $mock);
        $this->assertInstanceOf(Server::class, $node);
    }

    public function testChecksGroestlcoindExists()
    {
        $groestlcoind = "/some/invalid/path/groestlcoind";
        $dataDir = $this->registerTmpDir("datadir-check-groestlcoind-exists");

        $options = new NodeOptions($groestlcoind, $dataDir);
        $config = new Config([
            'daemon' => '1',
            'server' => '1',
            'regtest' => '1',
        ]);

        $service = new NodeService();
        $this->expectException(SetupException::class);
        $this->expectExceptionMessage("Path to groestlcoind executable is invalid");

        $service->createNewNode($options, $config, new FilesystemWriter());
    }

    public function testChecksDataDirNotExists()
    {
        $groestlcoind = $this->getGroestlcoindPath();
        $dataDir = $this->registerTmpDir("datadir-check-datadir-not-exists");
        mkdir($dataDir);

        $options = new NodeOptions($groestlcoind, $dataDir);
        $config = new Config([
            'daemon' => '1',
            'server' => '1',
            'regtest' => '1',
        ]);

        $service = new NodeService();
        $this->expectException(SetupException::class);
        $this->expectExceptionMessage("Cannot create a node in non-empty datadir");

        $service->createNewNode($options, $config, new FilesystemWriter());
    }

    public function testChecksGroestlcoindPathIsAnExecutable()
    {
        $groestlcoind = $this->registerTmpFile("non-executable-groestlcoind");
        file_put_contents($groestlcoind, "");

        $dataDir = $this->registerTmpDir("datadir-check-groestlcoind-executable");

        $options = new NodeOptions($groestlcoind, $dataDir);
        $config = new Config([
            'daemon' => '1',
            'server' => '1',
            'regtest' => '1',
        ]);

        $service = new NodeService();
        $this->expectException(SetupException::class);
        $this->expectExceptionMessage("Groestlcoind must be executable");

        $service->createNewNode($options, $config, new FilesystemWriter());
    }
}
