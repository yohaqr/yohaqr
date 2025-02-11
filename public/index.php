<?php

// Security Headers 
// header("X-Content-Type-Options: nosniff");
// header("X-Frame-Options: DENY");
// header("X-XSS-Protection: 1; mode=block");
// header("Content-Security-Policy: default-src 'self';");


use Yoha\Qr\Core\QrBuilder;
use Yoha\Qr\Bootstrap\ErrorHandler;
require __DIR__ . '/../vendor/autoload.php';

// Initialize the error handler
new ErrorHandler(true);

// Example: Trigger an error (will be caught and displayed)
// echo $undefined_variable;

// Example: Trigger an exception
// throw new Exception("This is a custom exception!");


// $result = $ts->getUri(data: 'Test QR I can change this data to change qr');


$ts = new QrBuilder();

$tss = $ts
    ->setWriterType('svg')
    ->setData('Testing SaveFile')
    ->saveToFile(name: 'testing_pdf', path: __DIR__.'/../storage/files/');
    
// $fileMG = new FileReader();

// dd($tss->getDataUri());

// $result = $tss->getDataUri();

// dd($result);
// Directly output the QR code
// header('Content-Type: '.$result->getMimeType());
// $r = $result->getString();

// Save it to a file
// $result->saveToFile(__DIR__.'/qrcode.png');

// Generate a data URI to include image data inline (i.e. inside an <img> tag)

// $result = _qr(data: "This is Test from Function");

// $dataUri = $result->getDataUri();

// echo $dataUri;



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YohaQr Base64 Image Example</title>
</head>
<body>
    <h1>Displaying an Image using data URI</h1>
    <?php

        echo($ts->readFile($tss));

        // // Assume $result contains the base64-encoded data
        // $mimeType = $tss->getMimeType(); // Example: Fetch MIME type dynamically

        // // Extract the file type from MIME type (e.g., 'image/png' -> 'png')
        // $fileType = explode('/', $mimeType)[1] ?? '';

        // if (in_array($fileType, ['png', 'jpg', 'jpeg', 'webp'])) {
            // echo '<img src="'. $tss . '" alt="Base64 Image">';
        // } elseif ($fileType === 'svg+xml') { // SVG MIME type is 'image/svg+xml'
            // echo '<object type="image/svg+xml" data="' . $tss->getDataUri() . '" width="300" height="300"></object>';
        // } elseif ($fileType === 'pdf') {
        //     echo '<embed src="data:application/pdf;' . $result . '" type="application/pdf" width="600" height="800">';
        // } else {
        //     echo 'Unsupported file type.';
        // }
    ?>
    <h1> This is just tet page. </h1>


</body>
</html>
