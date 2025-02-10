<?php

namespace Tests;

// tests/FileReaderTest.php
use stdClass;
use Yoha\Qr\Core\FileReader;
use PHPUnit\Framework\TestCase;

class FileReaderTest extends TestCase
{
    /**
     * Test for PNG MIME type.
     */
    public function testReadPng()
    {
        // Create a mock for the result object
        $resultMock = $this->createMock(stdClass::class);
        $resultMock->method('getMimeType')->willReturn('image/png');
        $resultMock->method('getDataUri')->willReturn('data:image/png;base64,examplebase64data');

        // Instantiate the FileReader
        $fileReader = new FileReader();

        // Call the readFile method
        $output = $fileReader->readFile($resultMock);

        // Assert that the output is correct
        $this->assertStringContainsString('<img src="data:image/png;base64,examplebase64data" alt="PNG Image">', $output);
    }

    /**
     * Test for WebP MIME type.
     */
    public function testReadWebp()
    {
        $resultMock = $this->createMock(stdClass::class);
        $resultMock->method('getMimeType')->willReturn('image/webp');
        $resultMock->method('getDataUri')->willReturn('data:image/webp;base64,examplebase64data');

        $fileReader = new FileReader();

        $output = $fileReader->readFile($resultMock);

        $this->assertStringContainsString('<img src="data:image/webp;base64,examplebase64data" alt="WebP Image">', $output);
    }

    /**
     * Test for SVG MIME type.
     */
    public function testReadSvg()
    {
        $resultMock = $this->createMock(stdClass::class);
        $resultMock->method('getMimeType')->willReturn('image/svg+xml');
        $resultMock->method('getDataUri')->willReturn('data:image/svg+xml;base64,examplebase64data');

        $fileReader = new FileReader();

        $output = $fileReader->readFile($resultMock);

        $this->assertStringContainsString('<object type="image/svg+xml" data="data:image/svg+xml;base64,examplebase64data" width="300" height="300"></object>', $output);
    }

    /**
     * Test for PDF MIME type.
     */
    public function testReadPdf()
    {
        $resultMock = $this->createMock(stdClass::class);
        $resultMock->method('getMimeType')->willReturn('application/pdf');
        $resultMock->method('getDataUri')->willReturn('data:application/pdf;base64,examplebase64data');

        $fileReader = new FileReader();

        $output = $fileReader->readFile($resultMock);

        $this->assertStringContainsString('<embed src="data:application/pdf;base64,examplebase64data" type="application/pdf" width="600" height="800">', $output);
    }

    /**
     * Test for unsupported MIME type.
     */
    public function testUnsupportedFileType()
    {
        $resultMock = $this->createMock(stdClass::class);
        $resultMock->method('getMimeType')->willReturn('application/unknown');
        $resultMock->method('getDataUri')->willReturn('data:application/unknown;base64,examplebase64data');

        $fileReader = new FileReader();

        $output = $fileReader->readFile($resultMock);

        $this->assertEquals('Unsupported file type.', $output);
    }
}
