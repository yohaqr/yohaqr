<?php

namespace Yoha\Qr\Interfaces;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use App\Exceptions\QrCodeBuilderException;
use Endroid\QrCode\Label\Font\FontInterface;
use Endroid\QrCode\Writer\Result\ResultInterface;

/**
* Interface QrCodeBuilderInterface
*
* Provides a fluent API to build and generate a QR code.
*/
interface QrCodeBuilderInterface
 {
    /**
    * Set the content to encode in the QR code.
    *
    * @param string $data The text or URL to encode.
    * @return self
    */

    public function setData( string $data ): self;

    /**
    * Set the character encoding.
    *
    * @param Encoding $encoding The encoding ( e.g., UTF-8 ).
    * @return self
    */

    public function setEncoding( $encoding ): self;

    /**
    * Set the error correction level.
    *
    * @param ErrorCorrectionLevel $errorCorrectionLevel The error correction level.
    * @return self
    */

    public function setErrorCorrectionLevel( ErrorCorrectionLevel $errorCorrectionLevel ): self;

    /**
    * Set the overall size ( in pixels ) of the QR code.
    *
    * @param int $size The size in pixels.
    * @return self
    */

    public function setSize( int $size ): self;

    /**
    * Set the margin ( in pixels ) around the QR code.
    *
    * @param int $margin The margin in pixels.
    * @return self
    */

    public function setMargin( int $margin ): self;

    /**
    * Set the round block size mode.
    *
    * @param RoundBlockSizeMode $roundBlockSizeMode The round block size mode.
    * @return self
    */

    public function setRoundBlockSizeMode( RoundBlockSizeMode $roundBlockSizeMode ): self;

    /**
    * Optionally set the logo file path.
    *
    * @param string|null $logoPath The path to the logo image.
    * @return self
    */

    public function setLogoPath( ?string $logoPath ): self;

    /**
    * Optionally set the logo resize width.
    *
    * @param int|null $logoResizeToWidth The width to which the logo should be resized.
    * @return self
    */

    public function setLogoResizeToWidth( ?int $logoResizeToWidth ): self;

    /**
    * Optionally enable or disable logo punchout background.
    *
    * @param bool $logoPunchoutBackground True to remove logo background.
    * @return self
    */

    public function setLogoPunchoutBackground( bool $logoPunchoutBackground ): self;

    /**
    * Optionally set label text.
    *
    * @param string|null $labelText The label text to display.
    * @return self
    */

    public function setLabelText( ?string $labelText ): self;

    /**
    * Optionally set the label font.
    *
    * @param FontInterface|null $labelFont The font to use for the label.
    * @return self
    */

    public function setLabelFont( $labelFont ): self;

    /**
    * Optionally set the label alignment.
    *
    * @param string|null $labelAlignment The label alignment ( e.g., center ).
    * @return self
    */

    
    /**
     * Optionally set the label alignment.
     *
     * @param LabelAlignment|null $labelAlignment The label alignment.
     * @return self
     */
    public function setLabelAlignment(?LabelAlignment $labelAlignment): self;

     /**
     * Build and generate the QR code.
     *
     * @return ResultInterface The generated QR code result.
     * @throws QrCodeBuilderException If required properties are missing or build fails.
     */
    public function generate(): ResultInterface;
}
