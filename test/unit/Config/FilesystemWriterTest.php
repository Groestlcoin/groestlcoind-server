<?php

declare(strict_types=1);

namespace BitWasp\Test\Groestlcoind\Config;

use BitWasp\Groestlcoind\Config\Config;
use BitWasp\Groestlcoind\Config\FilesystemWriter;
use BitWasp\Groestlcoind\Exception\GroestlcoindException;
use BitWasp\Test\Groestlcoind\TestCase;

class FilesystemWriterTest extends TestCase
{
    /**
     * @var FilesystemWriter
     */
    private $writer;

    public function setUp()
    {
        $this->writer = new FilesystemWriter();
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testCreateNew()
    {
        $filename = $this->registerTmpFile("test-create-new-config.conf");
        $this->assertFileNotExists($filename);

        $configItems = [
            'key' => 'value',
        ];

        $config = new Config($configItems);

        $this->writer->create($filename, $config);

        $this->assertFileExists($filename);
        $this->assertEquals(
            $config->all(),
            parse_ini_file($filename)
        );
    }

    public function testSaveWillOverwrite()
    {
        $filename = $this->registerTmpFile("test-save-will-overwrite.conf");
        $this->assertFileNotExists($filename);

        file_put_contents($filename, "");
        $this->assertEquals("", file_get_contents($filename));

        $config = new Config([
            'key' => 'value',
        ]);

        $this->writer->save($filename, $config);

        $this->assertFileExists($filename);
        $this->assertNotEquals("", file_get_contents($filename));
        $this->assertEquals(
            $config->all(),
            parse_ini_file($filename)
        );
    }

    public function testFileShouldNotExist()
    {
        $filename = $this->registerTmpFile("test-create-path-cannot-exist.conf");
        $this->assertFileNotExists($filename);

        file_put_contents($filename, "");

        $config = new Config([
            'key' => 'value',
        ]);

        $this->expectException(GroestlcoindException::class);
        $this->expectExceptionMessage("Cannot overwrite existing files with FilesystemWriter::create");

        $this->writer->create($filename, $config);
    }
}
