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

$text = stream_get_contents(STDIN);
$printer -> selectPrintMode(Printer::MODE_FONT_B);
$printer->feed();
$printer -> text($text);
$printer->feed(5);
$printer -> selectPrintMode(); // Reset Printer
$printer -> cut();


/* Always close the printer! */
$printer -> close();
