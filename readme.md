# uconverter

[uconverter](https://github.com/marzzelo/uconverter) is a customizable units converter.


## Install

```
composer require marzzelo/uconverter
```
## Usage
Instanciating a new units-converter:
```
$converter = new UnitsConverter([
		    'gf'  => 1000.0,
		    'N'   => 9.8066,
		    ...
		]);
```
(All quantities must be equivalent, ie.: 1000 gf == 9.8066 N).

Example: decaNewtons to kilograms:
```
print $converter->kgf(1, 'daN');  // 1.019721
```
Adding a new unit after construction:
```
$converter->lbf = 2.2046;

$converter->kgf(2.2046, 'klbf');  // 1000
```
### multiples & submultiples
Included prefixes:

```
                'da' => 10,
                'a'  => 1e-18,
                'f'  => 1e-15,
                'p'  => 1e-12,
                'n'  => 1e-9,
                'u'  => 1e-6,
                'm'  => 1e-3,
                'c'  => 0.01,
                'd'  => 0.1,
                'h'  => 100,
                'k'  => 1000,
                'M'  => 1e6,
                'G'  => 1e9,
                'T'  => 1e12,
                'P'  => 1e15,
                'E'  => 1e18,
```
Example (TeraNewtons to microNewtons):
```
$converter->uN(1.0, 'TN');  //  1.0e+18
`````
### Using Included Converters
To use included converters, use the static `UnitsConverter::getConverter(<name>)`.  
'force', 'length' and 'pressure' converters are included.

```
$fconverter = UnitsConverter::getConverter('force');
$pconverter = UnitsConverter::getConverter('pressure');
$lconverter = UnitsConverter::getConverter('length');

print $fconverter->N(1, 'kgf');     // 9.806652048217
print $pconverter->Pa(1, 'MPa');    // 1e6
print $lconverter->cm(1, 'in');     // 2.5400000025908
```

## Available built-in units 
### Force
_N, lbf, gf, ouncef, poundf, dyne, sthene_

and their multiples:

_daN, kN, klbf, kgf, kpoundf, etc._

### Pressure
_psi, Pa, gmm2, bar, atm, mHg, inHg, inH2O, torr_

and their multiples:

_kPa, hPa, MPa, kgmm2, cmHg, mmHg, mbar, etc._ 

### Length

_m, in, ft, hand, ly, mi, yd, pc, AU, Ao_

and their multiples:

_dm, cm, mm, um, dam, hm, km, ..._
