<?php
/**
 * This is a demo script for the functions of the PHP ESC/POS print driver,
 * Escpos.php.
 *
 * Most printers implement only a subset of the functionality of the driver, so
 * will not render this output correctly in all cases.
 *
 * @author Michael Billington <michael.billington@gmail.com>
 */
require __DIR__ . '/../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\EscposImage;

$connector = new NetworkPrintConnector("10.10.10.144", 9100);
$printer = new Printer($connector);

/* Initialize */
$printer -> initialize();


$json = json_decode(file_get_contents('php://input'), true);

$columns = [
    'device' => 24,
    'value' => 12,
    'time'   => 9,
    'date'   => 11
];

$text = "";
foreach($columns as $key => $length){
    if(isset($json[$key])){
        $text .= str_pad($json[$key], $length);
    }
}

if(isset($json['precut'])){
    $printer->feed();
    $printer->feed();
    $printer->cut();
    $printer->feed();
    $printer->feed();
}

if(isset($columns['text'])){
    $text = $json['text'];
}

$printer -> selectPrintMode(Printer::MODE_FONT_B);
$printer -> setLineSpacing(1);
$printer->feed();
$printer -> text($text);
$printer->feed();
$printer -> selectPrintMode(); // Reset Printer

if(isset($json['cut'])){
    $printer->feed();
    $printer->feed();
    $printer->cut(Printer::CUT_PARTIAL);
    $printer->feed();
    $printer->feed();
}


/* Always close the printer! */
$printer -> close();
