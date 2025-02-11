<?php

namespace Yoha\Qr\Core;

class FileReader
{
    public function readFile(mixed $result): string
    {
        if (!is_object($result) || 
            !method_exists($result, 'getMimeType') || 
            !method_exists($result, 'getDataUri')) {
            return 'Invalid file data.';
        }

        $mimeType = $result->getMimeType();
        $dataUri  = $result->getDataUri();

        if (!is_string($mimeType) || !is_string($dataUri) || empty($dataUri)) {
            return 'Invalid file data.';
        }

        $altTextMap = [
            'png' => 'PNG Image',
            'webp' => 'WebP Image',
            'svg+xml' => 'SVG Image',
            'pdf' => 'PDF Document'
        ];

        $mimeTypeParts = explode('/', $mimeType);
        $fileExtension = strtolower($mimeTypeParts[1] ?? '');
        $altText = $altTextMap[$fileExtension] ?? ucfirst($fileExtension) . ' Image';

        switch ($mimeType) {
            case 'image/png':
            case 'image/webp':
                return '<img src="' . htmlspecialchars($dataUri, ENT_QUOTES) . '" alt="' . htmlspecialchars($altText, ENT_QUOTES) . '" />';
            case 'image/svg+xml':
                return '<object type="image/svg+xml" data="' . htmlspecialchars($dataUri, ENT_QUOTES) . '" width="300" height="300"></object>';
            case 'application/pdf':
                return '<embed src="' . htmlspecialchars($dataUri, ENT_QUOTES) . '" type="application/pdf" width="600" height="800">';
            default:
                return 'Unsupported file type.';
        }
    }
}

