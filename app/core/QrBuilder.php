<?php

namespace Yoha\Qr\Core;


use Exception;
use RuntimeException;
use InvalidArgumentException;
use Yoha\Qr\Traits\EncodeQrWriter;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\FontInterface;
use Yoha\Qr\Exceptions\QrCodeBuilderException;
use Yoha\Qr\Interfaces\QrCodeBuilderInterface;
use Endroid\QrCode\Writer\Result\ResultInterface;


/**
 * Class QrCodeBuilder
 *
 * A fluent builder that wraps Endroid's latest QR code Builder (using named arguments)
 * and provides optional and required configuration with default values.
 */
class QrBuilder implements QrCodeBuilderInterface
{
    use EncodeQrWriter;

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
    /** @var int|null Optional logo Height */
    protected ?int $logoResizeToHeight = null;

    /** @var bool Whether to punch out the logo background (default: false) */
    protected bool $logoPunchoutBackground = false;

    /** @var string|null Optional label text */
    protected ?string $labelText = '';

    /** @var FontInterface|null Optional label font */
    protected ?FontInterface $labelFont = null;

    /** @var LabelAlignment|null Optional label alignment */
    protected ?LabelAlignment $labelAlignment = null;

    /** @var PngWriter The writer instance (default: new PngWriter()) */
    protected  $writer;

    /** @var array Default writer options (empty by default) */
    protected array $writerOptions = [];

    /** @var bool Whether to validate the result (default: false) */
    protected bool $validateResult = false;

    /**
     * @var 
     */
    public ?string $writertype = '';

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
        // defailt font
        $this->labelFont = new OpenSans(20);
        // Default error correction level set to High; adjust if needed.
        $this->errorCorrectionLevel = ErrorCorrectionLevel::High;
        $this->roundBlockSizeMode = RoundBlockSizeMode::Margin;
        $this->labelAlignment = LabelAlignment::Center;
        $this->setWriterType('svg');

        // dd($this->setWriter());
    }

    public function setWriterType($type = 'png')
    {
        $this->writertype = $type;
        $this->setWriter();
        $this->setWriterOption();
        return $this;
    }

    /**
     * Choose Writer
     * @return mixed
     */
    public function setWriter()
    {
        // dd($this->writer_type($this->writertype)['writer']);
        $this->writer = $this->writer_type($this->writertype)['writer'];
        return $this;
    }

    private function setWriterOption()
    {
        $wo = match($this->writertype) 
        {
            'png' => $this->writer_type($this->writertype)['options'],
            'svg'  => $this->writer_type($this->writertype)['options'],
            'webp' => $this->writer_type($this->writertype)['options'],
            'pdf'  => $this->writer_type($this->writertype)['options'],
        };

        $this->writerOptions = $wo;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function setData(string $data = "test QR"): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEncoding($encoding = 'UTF-8'): self
    {
        $this->encoding = new Encoding($encoding);
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
    public function setLogoResizeToHeight(?int $logoResizeToHeight): self
    {
        $this->logoResizeToHeight = $logoResizeToHeight;
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
    public function setLabelText(?string $labelText = ''): self
    {
        $this->labelText = $labelText;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLabelFont($labelFont): self
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
                logoResizeToHeight: $this->logoResizeToHeight,
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

    /**
     * Generates a QR code and returns its data URI.
     *
     * @param string $writerType The format of the QR code (e.g., 'png', 'svg').
     * @param string $data The data to encode in the QR code.
     * @param string $label The label text displayed below the QR code.
     * @return string The generated QR code as a data URI.
     * @throws InvalidArgumentException If any parameter is invalid.
     */
    public function getUri(
        string $writerType = 'png',
        string $data = 'Qr Test Data by YohaQr.',
        string $label = 'Scan Me. Centered.'
    ) {
        // Validate writer type
        $validWriterTypes = ['png', 'svg', 'jpg', 'gif'];
        if (!in_array(strtolower($writerType), $validWriterTypes, true)) {
            throw new InvalidArgumentException("Invalid writer type: $writerType. Allowed: " . implode(', ', $validWriterTypes));
        }

        // Validate data length
        if (empty(trim($data))) {
            throw new InvalidArgumentException("QR code data cannot be empty.");
        }

        // Validate label length (optional)
        if (strlen($label) > 100) {
            throw new InvalidArgumentException("Label text should not exceed 100 characters.");
        }

        // Set the QR code properties
        $this->setWriterType($writerType);
        $this->setData($data);
        $this->setLabelText($label);

        // Generate and return the QR code as a data URI
        $result = $this->generate();
        return $result->getDataUri();
    }

    /**
     * Saves the generated QR code to a file.
     *
     * @param string $name The name of the file (without extension).
     * @param string $path The directory path where the file should be saved.
     * @return mixed The generated QR code result object.
     * @throws InvalidArgumentException If parameters are invalid.
     * @throws RuntimeException If saving the file fails.
     */
    public function saveToFile(
        string $name = 'test',
        string $path = ''
    ) {
        // Validate filename
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $name)) {
            throw new InvalidArgumentException("Invalid filename: $name. Use only letters, numbers, hyphens, or underscores.");
        }

        // Ensure the path ends with a directory separator if provided
        if (!empty($path) && !is_dir($path)) {
            throw new InvalidArgumentException("Invalid path: $path. Directory does not exist.");
        }

        // Construct the absolute file path
        $ab_path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name . '.' . $this->writertype;

        // Generate QR code
        $result = $this->generate();

        // Attempt to save the file
        try {
            $result->saveToFile($ab_path);
        } catch (Exception $e) {
            throw new RuntimeException("Failed to save QR code to file: " . $e->getMessage());
        }

        return $result;
    }



}




