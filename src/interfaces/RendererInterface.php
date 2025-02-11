<?php

namespace Yoha\Qr\Interfaces;

interface RendererInterface
 {
    /**
    * Render the QR code as an image.
    *
    * @param array<array<int>> $matrix The QR code matrix from the encoder.
    * @param int $size The size of the output image.
    * @param int $margin The margin around the QR code.
    * @param string $color The color of the QR code ( hex or RGB ).
    * @return mixed The rendered image ( binary data, SVG string, etc. ).
    */

    public function render( array $matrix, int $size = 300, int $margin = 10, string $color = '#000000' );

}
