Below is an enhanced, advanced, and descriptive README example for YohaQR. This version includes detailed explanations, advanced examples, and clear usage instructions.


# YohaQR
*By [yohaqr](https://github.com/yohaqr)*

If you appreciate my work, please consider supporting me by visiting the [sponsor page](https://github.com/sponsors/yohacodes) or [buying me a coffee](https://www.buymeacoffee.com/yohacodes) ☕.

[![Latest Stable Version](http://img.shields.io/packagist/v/yoha/qr.svg)](https://packagist.org/packages/yoha/qr)
[![Build Status](https://github.com/yohaqr/yohaqr/actions/workflows/php.yml/badge.svg)](https://github.com/yohaqr/yohaqr/actions/workflows/php.yml)
[![Total Downloads](http://img.shields.io/packagist/dt/yoha/qr.svg)](https://packagist.org/packages/yoha/qr)
[![Monthly Downloads](http://img.shields.io/packagist/dm/yoha/qr.svg)](https://packagist.org/packages/yoha/qr)
[![License](http://img.shields.io/packagist/l/yoha/qr.svg)](https://packagist.org/packages/yoha/qr)

<img src="https://avatars.githubusercontent.com/u/198384864?s=200&v=4" alt="YohaQR" height="200" width="100%" />

## Overview

YohaQR is a powerful PHP Composer package that offers a simple, fluent API for generating dynamic QR codes using the latest [Endroid QR Code](https://github.com/endroid/qr-code) library. With YohaQR you can easily customize your QR codes by:

- **Custom Data Encoding:** Embed any text, URL, or custom data.
- **Adjustable Size and Margin:** Control the overall dimensions and the surrounding margin.
- **Error Correction & Encoding:** Select suitable error correction levels and encoding options.
- **Logo Integration:** Seamlessly add a custom logo with options for resizing and background removal.
- **Labeling:** Optionally append a label with custom fonts and alignment.

## Installation

Install YohaQR via Composer:

```bash
composer require yoha/qr
```

> **Note:** YohaQR requires PHP ^8.1. Ensure you have the latest version of PHP and Composer installed.

## Supported Output Types

YohaQR supports multiple output formats for your QR codes:
1. **PNG** (Default)
2. **PDF**
3. **WEBP**
4. **SVG**
5. *More formats coming soon...*

You can change the output format using the `setWriterType()` method.

## Changing the Writer Type

By default, YohaQR generates PNG images. To change the writer type (e.g., to PDF, WEBP, or SVG), call the `setWriterType()` method with the desired format:

```php
<?php 
use Yoha\Qr\Core\QrBuilder;

$qrBuilder = new QrBuilder();
$qrBuilder->setWriterType('pdf') // Change output type to PDF
          ->setData('http://yohacodes.pro.et')
          ->generate();
```

## Usage Examples

Below are several advanced examples demonstrating how to generate and display QR codes using YohaQR.

### Example 1: Basic QR Code Generation (PNG)

Generate a basic QR code and display it as a PNG image using the `getUri()` method.

```php
<?php

use Yoha\Qr\Core\QrBuilder;

// Include the Composer autoloader if you're using YohaQR in a custom PHP project
require __DIR__ . '/../vendor/autoload.php';

$qrBuilder = new QrBuilder();

// Generate the QR code and obtain the Data URI
$result = $qrBuilder->getUri();
?>

<!-- Display the generated QR code as an image -->
<img src="<?= $result ?>" alt="Generated QR Code (PNG)">
```

### Example 2: Advanced QR Code with Logo and Custom Settings

This example demonstrates how to create a fully customized QR code with a logo, resized dimensions, and custom data.

```php
<?php

use Yoha\Qr\QrBuilder;

$qrBuilder = new QrBuilder();

// Configure QR code with advanced settings
$result = $qrBuilder->setWriterType('png')                 // Change type if needed: 'pdf', 'webp', or 'svg'
                    ->setData('https://example.com')         // Data to encode
                    ->setLogoPath(__DIR__ . '/assets/logo.png')// Set the path to your logo image
                    ->setLogoResizeToWidth(50)                 // Resize logo width (in pixels)
                    ->setLogoPunchoutBackground(true)          // Optionally remove logo background
                    ->setMargin(2)                             // Set the QR code margin
                    ->generate();

// Retrieve the Data URI
$uri = $result->getDataUri();
?>

<!-- Display the QR code with a logo -->
<img src="<?= $uri ?>" alt="Advanced QR Code with Logo">
```

### Example 3: Generating and Saving a PDF QR Code

Generate a QR code in PDF format and save it to the server, then display it using an embed element.

```php
<?php

use Yoha\Qr\QrBuilder;

$qrBuilder = new QrBuilder();

// Generate a PDF QR code and save the file
$result = $qrBuilder->setMargin(2)
                    ->setWriterType('pdf')
                    ->setData('Testing SaveFile')
                    ->saveToFile(name: 'testing_pdf', path: __DIR__ . '/../storage/files/');

// Retrieve the Data URI for the saved file
$uri = $result->getDataUri();
?>

<!-- Embed the saved PDF in the browser -->
<embed src="<?= $uri ?>" type="application/pdf" width="600" height="800">
```

### Example 4: Dynamically Rendering QR Codes Based on MIME Type

YohaQR provides a helper method (`readFile()`) that checks the MIME type of the generated QR code and renders the appropriate HTML element automatically.

```php
<?php

use Yoha\Qr\QrBuilder;

$qrBuilder = new QrBuilder();

// Generate a QR code (change the writer type as needed: 'png', 'pdf', or 'svg')
$result = $qrBuilder->setMargin(2)
                    ->setWriterType('svg')
                    ->setData('Testing SaveFile')
                    ->generate();

// Render the QR code appropriately based on its MIME type
echo $qrBuilder->readFile($result);
```

## Logo Integration

The logo feature in YohaQR allows you to easily add a custom logo to your QR code. Just provide the logo’s file path with `setLogoPath()`. You can also:
- Resize the logo using `setLogoResizeToWidth()` or `setLogoResizeToHight()`
- Remove the logo's background with `setLogoPunchoutBackground()`

```php
<?php

use Yoha\Qr\QrBuilder;

$qrBuilder = new QrBuilder();

$result = $qrBuilder->setData('https://example.com')
                    ->setLogoPath(__DIR__ . '/assets/logo.png')
                    ->setLogoResizeToWidth(60)
                    ->setLogoPunchoutBackground(true)
                    ->generate();

echo '<img src="' . $result->getDataUri() . '" alt="QR Code with Logo">';
```

## Contributing

Contributions are welcome! If you find any bugs or have suggestions for improvements, please open an issue or submit a pull request on [GitHub](https://github.com/yohaqr/yohaqr). For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

Your contributions and support help make YohaQR even better. Happy coding!
```

---

You can copy and paste the above Markdown into your project's README file to provide users with a clear, advanced, and comprehensive guide on how to use YohaQR. Enjoy building your QR code solutions!