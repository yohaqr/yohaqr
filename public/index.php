<?php

use App\Core\QrBuilder;

require __DIR__ . '/../vendor/autoload.php';




// $ts = new QrBuilder();

// dd($ts->writer_type());
// $ts
// ->setWriterType('png')
// ->setData('Test QR I can change this data to change qr')
// ->generate();



// Directly output the QR code
// header('Content-Type: '.$result->getMimeType());
// echo $result->getString();

// Save it to a file
// $result->saveToFile(__DIR__.'/qrcode.png');

// Generate a data URI to include image data inline (i.e. inside an <img> tag)

$result = _qr(data: "This is Test from Function");
$dataUri = $result->getDataUri();

// echo $dataUri;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base64 Image Example</title>
</head>
<body>
    <h1>Displaying an Image using data URI</h1>
    <img src="data:image/png;<?= $dataUri ?>" alt="Base64 Image Example">
</body>
</html>
