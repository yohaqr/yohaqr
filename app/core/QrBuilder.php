<?php

namespace App\Core;


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use App\Exceptions\QrCodeBuilderException;
use App\Interfaces\QrCodeBuilderInterface;
use Endroid\QrCode\Label\Font\FontInterface;
use Endroid\QrCode\Writer\Result\ResultInterface;


/**
 * Class QrCodeBuilder
 *
 * A fluent builder that wraps Endroid's latest QR code Builder (using named arguments)
 * and provides optional and required configuration with default values.
 */
class QrBuilder implements QrCodeBuilderInterface
{
    /** @var string|null The QR code content (required) */
    protected ?string $data = null;

    /** @var Encoding The character encoding (default: UTF-8) */
    protected Encoding $encoding;

    /** @var ErrorCorrectionLevel The error correction level (default: High) */
    protected ErrorCorrectionLevel $errorCorrectionLevel;

    /** @var int The QR code size in pixels (default: 300) */
    protected int $size = 300;

    /** @var int The margin in pixels (default: 10) */
    protected int $margin = 10;

    /** @var RoundBlockSizeMode The round block size mode (default: Margin) */
    protected RoundBlockSizeMode $roundBlockSizeMode;

    /** @var string|null Optional logo path */
    protected ?string $logoPath = '';

    /** @var int|null Optional logo width */
    protected ?int $logoResizeToWidth = null;

    /** @var bool Whether to punch out the logo background (default: false) */
    protected bool $logoPunchoutBackground = false;

    /** @var string|null Optional label text */
    protected ?string $labelText = '';

    /** @var FontInterface|null Optional label font */
    protected ?FontInterface $labelFont = null;

    /** @var LabelAlignment|null Optional label alignment */
    protected ?LabelAlignment $labelAlignment = null;

    /** @var PngWriter The writer instance (default: new PngWriter()) */
    protected PngWriter $writer;

    /** @var array Default writer options (empty by default) */
    protected array $writerOptions = [];

    /** @var bool Whether to validate the result (default: false) */
    protected bool $validateResult = false;

    /**
     * QrCodeBuilder constructor.
    *
    * Sets default values for encoding, error correction, and round block mode.
    */
    public function __construct()
    {
        // Default Encoder
        $this->encoding = new Encoding('UTF-8');
        // Default Writer (PNG)
        $this->writer = new PngWriter();
        // defailt font
        $this->labelFont = new OpenSans(20);
        // Default error correction level set to High; adjust if needed.
        $this->errorCorrectionLevel = ErrorCorrectionLevel::High;
        $this->roundBlockSizeMode = RoundBlockSizeMode::Margin;
        $this->labelAlignment = LabelAlignment::Center;
    }
    /**
     * @inheritDoc
     */
    public function setData(string $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEncoding(Encoding $encoding): self
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setErrorCorrectionLevel(ErrorCorrectionLevel $errorCorrectionLevel): self
    {
        $this->errorCorrectionLevel = $errorCorrectionLevel;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setMargin(int $margin): self
    {
        $this->margin = $margin;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRoundBlockSizeMode(RoundBlockSizeMode $roundBlockSizeMode): self
    {
        $this->roundBlockSizeMode = $roundBlockSizeMode;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLogoPath(?string $logoPath): self
    {
        $this->logoPath = $logoPath;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLogoResizeToWidth(?int $logoResizeToWidth): self
    {
        $this->logoResizeToWidth = $logoResizeToWidth;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLogoPunchoutBackground(bool $logoPunchoutBackground): self
    {
        $this->logoPunchoutBackground = $logoPunchoutBackground;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLabelText(?string $labelText): self
    {
        $this->labelText = $labelText;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLabelFont(?FontInterface $labelFont): self
    {
        $this->labelFont = $labelFont;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLabelAlignment(?LabelAlignment $labelAlignment): self
    {
        $this->labelAlignment = $labelAlignment;
        return $this;
    }

    /**
     * Build and generate the QR code.
     *
     * This method validates that the required data is provided and then creates an instance of Endroid's Builder
     * using named arguments. Optional parameters are passed only if set.
     *
     * @return ResultInterface The generated QR code result.
     * @throws QrCodeBuilderException If required properties are missing or an error occurs during building.
     */
    public function generate(): ResultInterface
    {
        if (empty($this->data)) {
            throw new QrCodeBuilderException('QR code data is required.');
        }

        try {
            // Create a new Builder instance using named arguments.
            $builder = new Builder(
                writer: $this->writer,
                writerOptions: $this->writerOptions,
                validateResult: $this->validateResult,
                data: $this->data,
                encoding: $this->encoding,
                errorCorrectionLevel: $this->errorCorrectionLevel,
                size: $this->size,
                margin: $this->margin,
                roundBlockSizeMode: $this->roundBlockSizeMode,
                logoPath: $this->logoPath,
                logoResizeToWidth: $this->logoResizeToWidth,
                logoPunchoutBackground: $this->logoPunchoutBackground,
                labelText: $this->labelText,
                labelFont: $this->labelFont,
                labelAlignment: $this->labelAlignment
            );

            // Build and return the QR code result.
            return $builder->build();
        } catch (\Throwable $e) {
            throw new QrCodeBuilderException(
                'Failed to generate QR code: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


}
