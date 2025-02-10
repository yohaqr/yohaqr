# YohaQR
*By [yohaqr](https://github.com/yohaqr)*

If you like my work, please support me by visiting the [sponsor page](https://github.com/sponsors/yohaqr) or [buying me a coffee](https://www.buymeacoffee.com/yohaqr) :coffee:

[![Latest Stable Version](http://img.shields.io/packagist/v/yoha/qr.svg)](https://packagist.org/packages/yoha/qr)
[![Build Status](https://github.com/yohaqr/yohaqr/actions/workflows/php.yml/badge.svg)](https://github.com/yohaqr/yohaqr/actions/workflows/php.yml)
[![Total Downloads](http://img.shields.io/packagist/dt/yoha/qr.svg)](https://packagist.org/packages/yoha/qr)
[![Monthly Downloads](http://img.shields.io/packagist/dm/yoha/qr.svg)](https://packagist.org/packages/yoha/qr)
[![License](http://img.shields.io/packagist/l/yoha/qr.svg)](https://packagist.org/packages/yoha/qr)

Your project description goes here.


<img src="https://avatars.githubusercontent.com/u/198384864?s=200&v=4" alt="YohaQR" height="200" width="100%" />

YohaQr is a PHP Composer package that provides a simple, fluent API for generating QR codes using the latest [Endroid QR Code](https://github.com/endroid/qr-code) library. With QR Easy you can easily customize your QR codes with features such as:

- **Custom Data:** Encode any text or URL.
- **Adjustable Size and Margin:** Configure the overall size and surrounding margin.
- **Error Correction & Encoding:** Choose error correction levels and character encoding.
- **Logo Integration:** Add a custom logo (with optional resizing and background punchout).
- **Labeling:** Optionally add a label with a custom font and alignment.

## If you wanna to help or participate please let me know use the email down below
[YohaQr](https://avatars.githubusercontent.com/u/198384864?s=200&v=4)
## Installation

Install QR Easy via Composer:

```bash
composer require yoha/qr
```

> Make sure you have the latest version of PHP and Composer installed on your system.
* PHP ^8.1

# supported Types
1. PNG
2. PDF
3. WEBP
4. SVG

## Usage

QR Easy wraps the latest Endroid QR Code Builder with a fluent interface. Below is a basic example demonstrating how to generate a QR code with a custom logo and label.



```php
``` don't for get to include autoloader ```
```

```
I am working on examples!
```

### Logo Feature

When using the logo feature, simply provide a valid file path via the `setLogoPath()` method. Optionally, you can resize the logo using `setLogoResizeToWidth()` and remove the background behind the logo with `setLogoPunchoutBackground()`.

## Running Tests

QR Easy uses PHPUnit for unit testing. To run the tests, install PHPUnit (if you haven’t already) and execute:

```bash
you need to install phpunit test to try this but I am preparing cli 
```

```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```

## Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request with improvements or bug fixes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
