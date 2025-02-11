<?php
declare(strict_types=1);

namespace Tests;

// require __DIR__ ."/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Yoha\Qr\Core\FileReader;

/**
 * A dummy class to simulate the generated result object.
 */
class DummyResult
{
    public function getMimeType(): string
    {
        // Return the mime type of the result
        return 'image/png'; // Example, this should be overridden in mock
    }

    public function getDataUri(): string
    {
        // Return the data URI of the result
        return ''; // Example, this should be overridden in mock
    }
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

    /**
     * Test PNG file handling.
     */
    public function testReadPng(): void
    {
        $mimeType = 'image/png';
        $dataUri  = 'data:image/png;base64,base64EncodedData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        $expected = '<img src="' . $dataUri . '" alt="PNG Image" />';
        $this->assertEquals($expected, $output);
    }

    /**
     * Test WebP file handling.
     */
    public function testReadWebp(): void
    {
        // For WebP, assume getDataUri() returns the full WebP data URI.
        $mimeType = 'image/webp';
        $dataUri  = 'data:image/webp;base64,base64WebpData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        // Expect the output to include the complete data URI in the <img> tag, with a self-closing tag.
        $expected = '<img src="' . $dataUri . '" alt="WebP Image" />';
        $this->assertEquals($expected, $output);
    }


    /**
     * Test SVG file handling.
     */
    public function testReadSvg(): void
    {
        $mimeType = 'image/svg+xml';
        $dataUri  = 'data:image/svg+xml;base64,svgData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        $expected = '<object type="image/svg+xml" data="' . $dataUri . '" width="300" height="300"></object>';
        $this->assertEquals($expected, $output);
    }

    /**
     * Test PDF file handling.
     */
    public function testReadPdf(): void
    {
        $mimeType = 'application/pdf';
        $dataUri  = 'data:application/pdf;base64,pdfData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        $expected = '<embed src="' . $dataUri . '" type="application/pdf" width="600" height="800">';
        $this->assertEquals($expected, $output);
    }

    /**
     * Test unsupported file type handling.
     */
    public function testUnsupportedFileType(): void
    {
        $mimeType = 'application/octet-stream';
        $dataUri  = 'data:application/octet-stream;base64,unknownData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        $this->assertEquals('Unsupported file type.', $output);
    }

    /**
     * Test when Data URI is empty or invalid.
     */
    public function testEmptyDataUri(): void
    {
        $mimeType = 'image/png';
        $dataUri  = ''; // Empty Data URI
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        $this->assertEquals('Invalid file data.', $output); // The expected output
    }


    /**
     * Test when MimeType is invalid.
     */
    public function testInvalidMimeType(): void
    {
        $mimeType = 'unknown/mime';
        $dataUri  = 'data:unknown/mime;base64,invalidData';
        $result   = $this->createResultStub($mimeType, $dataUri);

        $fileReader = new FileReader();
        $output     = $fileReader->readFile($result);

        $this->assertEquals('Unsupported file type.', $output);
    }
}
