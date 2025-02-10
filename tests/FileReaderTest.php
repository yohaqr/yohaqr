<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Yoha\Qr\Core\FileReader;

/**
 * A dummy class to simulate the generated result object.
 */
class DummyResult
{
    public function getMimeType() {}
    public function getDataUri() {}
}

class FileReaderTest extends TestCase
{
    /**
     * Create a stub for the result object using DummyResult.
     *
     * @param string $mimeType The MIME type to simulate.
     * @param string $dataUri  The complete data URI to simulate.
     * @return DummyResult
     */
    private function createResultStub(string $mimeType, string $dataUri): DummyResult
    {
        $result = $this->getMockBuilder(DummyResult::class)
                       ->onlyMethods(['getMimeType', 'getDataUri'])
                       ->getMock();
        $result->method('getMimeType')->willReturn($mimeType);
        $result->method('getDataUri')->willReturn($dataUri);
        return $result;
    }

    public function testReadPng(): void
    {
        // For PNG, we assume getDataUri() returns a complete Data URI.
        $mimeType = 'image/png';
        $dataUri  = 'data:image/png;base64,base64EncodedData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        // Expect the output to simply wrap the full data URI in an <img> tag.
        $expected = '<img src="' . $dataUri . '" alt="PNG Image" />';
        $this->assertEquals($expected, $output);
    }

    public function testReadWebp(): void
    {
        // For WebP, assume getDataUri() returns the full WebP data URI.
        $mimeType = 'image/webp';
        $dataUri  = 'data:image/webp;base64,base64WebpData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        // Expect the output to include the complete data URI in the <img> tag.
        $expected = '<img src="' . $dataUri . '" alt="WebP Image">';
        $this->assertEquals($expected, $output);
    }


    public function testReadSvg(): void
    {
        // For SVG, assume getDataUri() returns the full SVG data URI.
        $mimeType = 'image/svg+xml';
        $dataUri  = 'data:image/svg+xml;base64,svgData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        $expected = '<object type="image/svg+xml" data="' . $dataUri . '" width="300" height="300"></object>';
        $this->assertEquals($expected, $output);
    }

    public function testReadPdf(): void
    {
        // For PDF, assume getDataUri() returns the full PDF data URI.
        $mimeType = 'application/pdf';
        $dataUri  = 'data:application/pdf;base64,pdfData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        $expected = '<embed src="' . $dataUri . '" type="application/pdf" width="600" height="800">';
        $this->assertEquals($expected, $output);
    }

    public function testUnsupportedFileType(): void
    {
        // For an unsupported MIME type, expect the output to be 'Unsupported file type.'
        $mimeType = 'application/octet-stream';
        $dataUri  = 'data:application/octet-stream;base64,unknownData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        $this->assertEquals('Unsupported file type.', $output);
    }
}
