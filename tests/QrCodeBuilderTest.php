<?php

namespace Tests;

use App\Core\QrBuilder;
use PHPUnit\Framework\TestCase;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use App\Exceptions\QrCodeBuilderException;
use Endroid\QrCode\Writer\Result\ResultInterface;

/**
 * Class QrCodeBuilderTest
 *
 * This class tests the functionality of the QrCodeBuilder.
 */
class QrCodeBuilderTest extends TestCase
{
    /**
     * Test that the QR code builder generates a valid result.
     *
     * This test sets the required and some optional parameters and ensures
     * that the generated result is an instance of ResultInterface, that it has
     * a non-empty output string, and that the MIME type starts with "image/".
     *
     * @return void
     */
    public function testGenerateReturnsResultInstance(): void
    {
        $qrBuilder = new QrBuilder();
        $result = $qrBuilder
            ->setWriterType()
            ->setData('Test QR Code')
            ->setEncoding()
            // ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            // ->setSize(300)
            // ->setMargin(10)
            // ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            // ->setLabelText('Test Label')
            // ->setLabelFont()
            // ->setLabelAlignment(LabelAlignment::Center)
            ->generate();

        // Assert that the result is an instance of Endroid's ResultInterface.
        $this->assertInstanceOf(ResultInterface::class, $result);

        // Ensure that the output string is not empty.
        $this->assertNotEmpty($result->getString());

        // Verify that the MIME type indicates an image (e.g., "image/png").
        $this->assertStringStartsWith('image/', $result->getMimeType());
    }

    /**
     * Test that attempting to generate a QR code without setting required data
     * results in a QrCodeBuilderException.
     *
     * @return void
     */
    public function testGenerateWithoutDataThrowsException(): void
    {
        $this->expectException(QrCodeBuilderException::class);
        $this->expectExceptionMessage('QR code data is required.');

        $qrBuilder = new QrBuilder();
        // Not setting the data should trigger an exception.
        $qrBuilder->generate();
    }
}
