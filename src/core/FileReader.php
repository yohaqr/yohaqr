<?php

namespace Yoha\Qr\Core;

class FileReader
{
    public function readFile($result): string
    {
        $mimeType = $result->getMimeType();
        $dataUri  = $result->getDataUri();

        // Handle empty Data URI
        if (empty($dataUri)) {
            return 'Invalid file data.';
        }

        // Map MIME types to friendly alt text (preserving casing)
        $altTextMap = [
            'png' => 'PNG Image',
            'webp' => 'WebP Image',
            'svg+xml' => 'SVG Image',
            'pdf' => 'PDF Document'
        ];

        // Extract file extension and lookup alt text
        $mimeTypeParts = explode('/', $mimeType);
        $fileExtension = strtolower($mimeTypeParts[1] ?? '');

        // Default alt text if the extension is not recognized
        $altText = $altTextMap[$fileExtension] ?? ucfirst($fileExtension) . ' Image';

        switch ($mimeType) {
            case 'image/png':
            case 'image/webp':
                return '<img src="' . $dataUri . '" alt="' . $altText . '" />';
            case 'image/svg+xml':
                return '<object type="image/svg+xml" data="' . $dataUri . '" width="300" height="300"></object>';
            case 'application/pdf':
                return '<embed src="' . $dataUri . '" type="application/pdf" width="600" height="800">';
            default:
                return 'Unsupported file type.';
        }
    }
}
