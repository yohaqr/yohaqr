<?php

use Yoha\Qr\Core\QrBuilder;
use Endroid\QrCode\Writer\Result\ResultInterface;

/**
* @uses QrBuilder Name
*/
if ( !function_exists( '_qr' ) ) 
 {
    function _qr(
        string $data,
        string $writerType = 'png',
        string $logoPath = '',
        string $label = 'Test'
    ): mixed
    {
        /**
        * @return string|QrBuilder
        */

        $qrCode = new QrBuilder();

        $result = $qrCode
        ->setWriterType( $writerType )
        ->setData( $data )
        ->setLogoPath( $logoPath )
        ->setLabelText( $label )
        ->generate();
        // Ensure generate() returns the expected type

        return $result;

    }
}