<?php

namespace App\Interfaces;


interface EncoderInterface
{
    /**
     * Encode the given data into a QR code matrix.
     *
     * @param string $data The data to encode.
     * @param string $errorCorrection The error correction level (L, M, Q, H).
     * @return array The QR code matrix.
     */
    public function encode(string $data, string $errorCorrection = 'M'): array;
}
