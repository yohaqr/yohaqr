<?php

namespace App\Interfaces;


interface QrCodeBuilderInterface
{
    /**
     * Set the text or URL for the QR code.
     *
     * @param string $text The text to encode.
     * @return self
     */
    public function setText(string $text): self;

    /**
     * Set the size of the QR code.
     *
     * @param int $size The size in pixels.
     * @return self
     */
    public function setSize(int $size): self;

    /**
     * Set the margin around the QR code.
     *
     * @param int $margin The margin in pixels.
     * @return self
     */
    public function setMargin(int $margin): self;

    /**
     * Set the error correction level.
     *
     * @param string $level Error correction level (L, M, Q, H).
     * @return self
     */
    public function setErrorCorrectionLevel(string $level): self;

    /**
     * Set the color of the QR code.
     *
     * @param string $color Color in hex format (e.g., #000000).
     * @return self
     */
    public function setColor(string $color): self;

    /**
     * Generate the QR code and return the rendered output.
     *
     * @return mixed The QR code image data.
     */
    public function generate();
}
