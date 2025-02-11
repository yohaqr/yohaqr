<?php

namespace Yoha\Qr\Core;

use Exception;
use Throwable;
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
use Endroid\QrCode\Writer\WriterInterface;
use Endroid\QrCode\Label\Font\FontInterface;
use Yoha\Qr\Exceptions\QrCodeBuilderException;
use Yoha\Qr\Interfaces\QrCodeBuilderInterface;
use Endroid\QrCode\Writer\Result\ResultInterface;

class QrBuilder extends FileReader implements QrCodeBuilderInterface
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

    /** @var int|null Optional logo height */
    protected ?int $logoResizeToHeight = null;

    /** @var bool Whether to punch out the logo background (default: false) */
    protected bool $logoPunchoutBackground = false;

    /** @var string|null Optional label text */
    protected ?string $labelText = '';

    /** @var FontInterface|null Optional label font */
    protected ?FontInterface $labelFont = null;

    /** @var LabelAlignment|null Optional label alignment */
    protected ?LabelAlignment $labelAlignment = null;

    /**
     * The writer instance.
     * We'll assume all writer classes implement WriterInterface.
     *
     * @var WriterInterface
     */
    protected WriterInterface $writer;

    /**
     * Default writer options.
     *
     * @var array<string, mixed>
     */
    protected array $writerOptions = [];

    /** @var bool Whether to validate the result (default: false) */
    protected bool $validateResult = false;

    /** @var string Writer type; we require a non-null string */
    protected string $writertype = '';

    public function __construct(
        public string $getData = ''
    )
    {
        // Default Encoder
        $this->encoding = new Encoding('UTF-8');
        // Default font for label
        $this->labelFont = new OpenSans(20);
        // Default error correction level set to High; adjust if needed.
        $this->errorCorrectionLevel = ErrorCorrectionLevel::High;
        $this->roundBlockSizeMode = RoundBlockSizeMode::Margin;
        $this->labelAlignment = LabelAlignment::Center;
        // Set an initial writer type (e.g. 'svg')
        $this->setWriterType('svg');
    }

    /**
     * Sets the writer type.
     *
     * @param string $type The writer type (e.g., 'png', 'svg').
     * @return static
     */
    public function setWriterType(string $type = 'png'): static
    {
        $this->writertype = $type;
        $this->setWriter();
        $this->setWriterOption();
        return $this;
    }

    /**
     * Chooses and sets the writer instance.
     *
     * @return static
     */
    public function setWriter(): static
    {
        // Retrieve writer data from your writer_type() method.
        $writerData = $this->writer_type($this->writertype);

        // Ensure the 'writer' key exists.
        if (!isset($writerData['writer'])) {
            throw new RuntimeException("Invalid writer data returned for writer type '{$this->writertype}'.");
        }

        // Ensure that the returned writer is an instance of WriterInterface.
        if (!$writerData['writer'] instanceof WriterInterface) {
            throw new RuntimeException("Returned writer is not an instance of WriterInterface.");
        }

        // Now safely assign.
        $this->writer = $writerData['writer'];
        return $this;
    }



    /**
     * Sets writer options based on the writer type.
     *
     * @return static
     */
    private function setWriterOption(): static
    {
        // Retrieve writer data.
        $writerData = $this->writer_type($this->writertype);

        // Ensure the 'options' key exists and is an array.
        if (!isset($writerData['options']) || !is_array($writerData['options'])) {
            throw new RuntimeException("Invalid writer options returned for writer type '{$this->writertype}'.");
        }

        // Prepare a new array ensuring keys are strings.
        $options = [];
        foreach ($writerData['options'] as $key => $value) {
            if (!is_string($key)) {
                throw new RuntimeException("Writer options keys must be strings.");
            }
            $options[$key] = $value;
        }

        $this->writerOptions = $options;
        return $this;
    }


    // private function setWriterOption(): static
    // {
    //     $wo = match($this->writertype) {
    //         'png'  => $this->writer_type($this->writertype)['options'],
    //         'svg'  => $this->writer_type($this->writertype)['options'],
    //         'webp' => $this->writer_type($this->writertype)['options'],
    //         'pdf'  => $this->writer_type($this->writertype)['options'],
    //         default => []
    //     };
    //     $this->writerOptions = $wo;
    //     return $this;
    // }

    public function setData(string $data = "test QR"): self
    {
    
        $this->data = empty($this->getData) ? $data : $this->getData;
        $this->generate();
    
        return $this;
    }

    public function setEncoding($encoding): self
    {
        $encoding = 'UTF-8';
        $this->encoding = new Encoding($encoding);
        return $this;
    }

    public function setErrorCorrectionLevel(ErrorCorrectionLevel $errorCorrectionLevel): self
    {
        $this->errorCorrectionLevel = $errorCorrectionLevel;
        return $this;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function setMargin(int $margin): self
    {
        $this->margin = $margin;
        return $this;
    }

    public function setRoundBlockSizeMode(RoundBlockSizeMode $roundBlockSizeMode): self
    {
        $this->roundBlockSizeMode = $roundBlockSizeMode;
        return $this;
    }

    public function setLogoPath(?string $logoPath): self
    {
        $this->logoPath = $logoPath;
        return $this;
    }

    public function setLogoResizeToWidth(?int $logoResizeToWidth): self
    {
        $this->logoResizeToWidth = $logoResizeToWidth;
        return $this;
    }

    public function setLogoResizeToHeight(?int $logoResizeToHeight): self
    {
        $this->logoResizeToHeight = $logoResizeToHeight;
        return $this;
    }

    public function setLogoPunchoutBackground(bool $logoPunchoutBackground): self
    {
        $this->logoPunchoutBackground = $logoPunchoutBackground;
        return $this;
    }

    public function setLabelText(?string $labelText = ''): self
    {
        $this->labelText = $labelText;
        return $this;
    }

    public function setLabelFont($labelFont): self
    {
        $this->labelFont = $labelFont;
        return $this;
    }

    public function setLabelAlignment($labelAlignment): self
    {
        $this->labelAlignment = $labelAlignment;
        return $this;
    }

    /**
     * Builds and generates the QR code.
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
                logoPath: $this->logoPath ?? '',
                logoResizeToWidth: $this->logoResizeToWidth,
                logoResizeToHeight: $this->logoResizeToHeight,
                logoPunchoutBackground: $this->logoPunchoutBackground,
                labelText: $this->labelText ?? '',
                labelFont: $this->labelFont ?? new OpenSans(20),
                labelAlignment: $this->labelAlignment ?? LabelAlignment::Center
            );

            return $builder->build();
        } catch (Throwable $e) {
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
    ): string {
        $validWriterTypes = ['png', 'svg', 'jpg', 'gif'];
        if (!in_array(strtolower($writerType), $validWriterTypes, true)) {
            throw new InvalidArgumentException("Invalid writer type: $writerType. Allowed: " . implode(', ', $validWriterTypes));
        }

        if (empty(trim($data))) {
            throw new InvalidArgumentException("QR code data cannot be empty.");
        }

        if (strlen($label) > 100) {
            throw new InvalidArgumentException("Label text should not exceed 100 characters.");
        }

        $this->setWriterType($writerType);
        $this->setData($data);
        $this->setLabelText($label);

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
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $name)) {
            throw new InvalidArgumentException("Invalid filename: $name. Use only letters, numbers, hyphens, or underscores.");
        }

        if (!empty($path) && !is_dir($path)) {
            throw new InvalidArgumentException("Invalid path: $path. Directory does not exist.");
        }

        $ab_path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name . '.' . $this->writertype;
        $result = $this->generate();

        try {
            $result->saveToFile($ab_path);
        } catch (Exception $e) {
            throw new RuntimeException("Failed to save QR code to file: " . $e->getMessage());
        }

        return $result;
    }
}
