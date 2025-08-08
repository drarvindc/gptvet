<?php
namespace Picqer\Barcode;
class BarcodeGeneratorPNG {
    const TYPE_CODE_128 = 'code128';
    public function getBarcode($text, $type) {
        return "BARCODE-" . $text;
    }
}
?>