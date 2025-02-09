<?php

namespace App\Traits;

use Endroid\QrCode\Writer\PngWriter;

trait EncodeQrWriter
{

    public function writer_type(string $type)
    {
        return match ($type) {
            'png' => $this->pngWrite(),
            'jpg' => $this->jpgWriter(),
            'svg' => $this->svgWriter(),
            'webp' => $this->webpWriter(),
            'pdf' => $this->pdfWriter()
        };
    }
    /**
     * 
     */
    public function pngWrite():array
    {
        $writer = new PngWriter();
        $option = [];

        return [
            "writer" => $writer,
            "option" => $option
        ];
    }

    /**
     * 
     */
    public function jpgWriter()
    {

    }

    /**
     * 
     */
    public function svgWriter()
    {

    }


    /**
     * web 
     */
    public function webpWriter()
    {

    }

    /**
     * 
     */
    public function pdfWriter()
    {

    }




}