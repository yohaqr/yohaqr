<?php

namespace App\Traits;

use Endroid\QrCode\Writer\PdfWriter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\WebPWriter;



trait EncodeQrWriter
{
    /**
     * Returns a writer instance based on the provided type.
     * 
     * @param string $type QR Code format type (png, jpg, svg, webp, pdf)
     * @return array Associative array with 'writer' and 'options'
     * @throws \InvalidArgumentException If an unsupported type is provided
     */
    public function writer_type(string $type = 'png'): array
    {
        return match (strtolower($type)) {
            'png'  => $this->pngWrite(),
            'svg'  => $this->svgWriter(),
            'webp' => $this->webpWriter(),
            'pdf'  => $this->pdfWriter(),
            default => throw new \InvalidArgumentException("Unsupported QR writer type: $type")
        };
    }

    /**
     * PNG Writer Configuration
     * 
     * @return array
     */
    private function pngWrite(): array
    {
        return [
            'writer' => new PngWriter(),
            'options' => ['compression_level' => -1] // Default zlib compression level
        ];
    }


    /**
     * SVG Writer Configuration
     * 
     * @return array
     */
    private function svgWriter(): array
    {
        return [
            'writer' => new SvgWriter(),
            'options' => [
                'block_id' => 'block',
                'exclude_xml_declaration' => false,
                'exclude_svg_width_and_height' => false,
                'force_xlink_href' => false,
                'compact' => true
            ]
        ];
    }

    /**
     * WebP Writer Configuration
     * 
     * @return array
     */
    private function webpWriter(): array
    {
        return [
            'writer' => new WebPWriter(),
            'options' => ['quality' => 80] // Default WebP quality
        ];
    }

    /**
     * PDF Writer Configuration
     * 
     * @return array
     */
    private function pdfWriter(): array
    {
        return [
            'writer' => new PdfWriter(),
            'options' => [
                'unit' => 'mm', // Default unit of measurement
                'x' => 0,
                'y' => 0,
                'link' => null // No default link
            ]
        ];
    }

}

