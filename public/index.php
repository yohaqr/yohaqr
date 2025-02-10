<?php

use App\Core\QrBuilder;

require __DIR__ . '/../vendor/autoload.php';




$ts = new QrBuilder();

// dd($ts->writer_type());
dd(
    $ts
            ->setWriterType('pdf')
            ->setData('Test QR')
            ->generate()
);