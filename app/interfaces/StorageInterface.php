<?php


namespace App\Interfaces;


interface StorageInterface
{
    /**
     * Save the QR code image to a file.
     *
     * @param mixed $qrCode The generated QR code image.
     * @param string $path The file path to save the image.
     * @return bool True if saved successfully, false otherwise.
     */
    public function saveToFile($qrCode, string $path): bool;

    // public function deleteFromFile(string $path): bool;

    
}
