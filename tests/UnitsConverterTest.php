<?php

namespace Marzzelo\Tests;

use PHPUnit\Framework\TestCase;
use Marzzelo\Uconverter\UnitsConverter;
use Marzzelo\Uconverter\Exceptions\UnitsConverterException;

class UnitsConverterTest extends TestCase
{
	/** @test */
	public function assigning_alpha_value_throws_exception()
	{
		$converter = new UnitsConverter;
		$this->expectException('Marzzelo\Uconverter\Exceptions\UnitsConverterException');
		$this->expectExceptionMessage('The [kgf] value must be numeric');

		$converter->kgf = 'x';
	}

	/** @test */
	public function assigning_zero_value_throws_exception()
	{
		$converter = new UnitsConverter;
		$this->expectException('Marzzelo\Uconverter\Exceptions\UnitsConverterException');
		$this->expectExceptionMessage('The [kgf] value must be greater than zero');

		$converter->kgf = 0;
	}

	/** @test */
	public function assigning_zero_value_in_the_constructor_throws_exception()
	{
		$this->expectException('Marzzelo\Uconverter\Exceptions\UnitsConverterException');
		$this->expectExceptionMessage('The [kgf] value must be greater than zero');

		$converter = new UnitsConverter([
			'kgf' => 0,
		]);
	}

	/** @test */
	public function it_creates_a_property_dinamically()
	{
		$converter = new UnitsConverter([
			'kgf' => 1,
			'N'   => 9.8066,
		]);

		$this->assertEquals(1, $converter->kgf);

		$converter->lbf = 2.2046;
		$this->assertEquals(2.2046, $converter->lbf);
		$this->assertEquals(1.0, $converter->kgf(2.2046, 'lbf'));

		$this->assertEquals(1000, $converter->lbf(1, 'klbf'));
		$this->assertEquals(1000.0, $converter->kgf(2.2046, 'klbf'));
	}

	/** @test */
	public function it_converts_base_units()
	{
		$converter = new UnitsConverter([
			'gf' => 1000.0,
			'N'  => 9.8066,
		]);

		$this->assertEquals(98.066, $converter->N(10.0, 'kgf'));
		$this->assertEquals(10.0 / 9.8066, $converter->kgf(10.0, 'N'));
	}

	/** @test */
	public function it_converts_derived_units()
	{
		$converter = new UnitsConverter([
			'gf' => 1000.0,
			'N'  => 9.8066,
		]);

		$this->assertEquals(10 / 9.8066, $converter->kgf(1.0, 'daN'));     // derived to base
		$this->assertEquals(9.8066 / 10, $converter->daN(1.0, 'kgf'));     // base to derived
		$this->assertEquals(100.0, $converter->daN(1.0, 'kN'));            // derived to derived
		$this->assertEquals(1.0e+18, $converter->uN(1.0, 'TN'));           // big number
		$converter->lbf = 2.2046;                                          // dyn. added unit
		$this->assertEquals(1.0, 1.0e+18 * $converter->Tlbf(1.0, 'ulbf')); // small number
	}

	/** @test */
	public function it_throws_an_exception_when_unknown_FROM_base()
	{
		$converter = new UnitsConverter([
			'x' => 1.0,
		]);

		$this->expectExceptionMessage('Unknown Unit: y');
		$converter->x(1.0, 'y');   // unknown FROM
	}

	/** @test */
	public function it_throws_an_exception_when_unknown_TO_base()
	{
		$converter = new UnitsConverter([
			'x' => 1.0,
		]);

		$this->expectException(UnitsConverterException::class);
		$this->expectExceptionMessage("Unknown Unit: y");
		$converter->y(1.0, 'x');   // Unknown TO
	}

	/** @test */
	public function it_throws_an_exception_when_unknown_TO__multiple_base()
	{
		$converter = new UnitsConverter([
			'x' => 1.0,
		]);

		$this->expectException(UnitsConverterException::class);
		$this->expectExceptionMessage('Unknown Base Unit: y');
		$converter->ky(1.0, 'x');   // Unknown TO
	}


	/** @test */
	public function it_gets_included_converters()
	{
		$fconversor = UnitsConverter::getConverter('force');
		$pconversor = UnitsConverter::getConverter('pressure');
		$lconversor = UnitsConverter::getConverter('length');

		$this->assertEquals(9.806652048217, $fconversor->N(1, 'kgf'));
		$this->assertEquals(1e6, $pconversor->Pa(1, 'MPa'));
		$this->assertEquals(2.5400000025908, $lconversor->cm(1, 'in'));
	}

}