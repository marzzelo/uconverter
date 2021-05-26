<?php


namespace Doba;

use Doba\Uconverter\UnitsConverter;
use Doba\Uconverter\ConverterStarter;
use Doba\Uconverter\Facades\FConverter;
use Doba\Uconverter\Facades\PConverter;

require __DIR__ . '/../vendor/autoload.php';

ConverterStarter::start();

print "\n\n1 daN = " . FConverter::N(1, 'daN') . "N";
print "\n1 MPa = " . PConverter::psi(1, 'MPa') . 'psi';

$dconv = new UnitsConverter(['m' => 1]);
print "\n1 km = " . $dconv->m(1, 'km') . 'm';








