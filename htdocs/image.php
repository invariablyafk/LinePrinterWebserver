<?php
/* Example print-outs using the older bit image print command */
require __DIR__ . '/../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

$connector = new FilePrintConnector("php://stdout");
$printer = new Printer($connector);

try {
    $tux = EscposImage::load("resources/tux.png", false);

    $printer -> text("May 7th 2011\n\n");
    
    $printer -> bitImage($tux);
    $printer -> text("Shadow\n");
    $printer -> feed();
    
} catch (Exception $e) {
    /* Images not supported on your PHP, or image file not found */
    $printer -> text($e -> getMessage() . "\n");
}

$printer -> cut();
$printer -> close();
