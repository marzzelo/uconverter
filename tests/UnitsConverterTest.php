<?php

use PHPUnit\Framework\TestCase;
use Marzzelo\Uconverter\UnitsConverter;
use Marzzelo\Uconverter\ConverterStarter;
use Marzzelo\Uconverter\Facades\FConverter;
use Marzzelo\Uconverter\Facades\PConverter;
use Marzzelo\Uconverter\Exceptions\UnitsConverterException;

class UnitsConverterTest extends TestCase
{
	/** @test */
	public function it_creates_a_property_dinamically()
	{
		$conversor = new UnitsConverter([
			'kgf' => 1,
			'N'   => 9.8066,
		]);

		$this->assertEquals(1, $conversor->kgf);

		$conversor->lbf = 2.2046;
		$this->assertEquals(2.2046, $conversor->lbf);
		$this->assertEquals(1.0, $conversor->kgf(2.2046, 'lbf'));
		$this->assertEquals(1000.0, $conversor->kgf(2.2046, 'klbf'));
	}

	/** @test */
	public function it_converts_base_units()
	{
		$conversor = new UnitsConverter([
			'gf' => 1000.0,
			'N'   => 9.8066,
		]);

		$this->assertEquals(98.066, $conversor->N(10.0, 'kgf'));
		$this->assertEquals(10.0 / 9.8066, $conversor->kgf(10.0, 'N'));
	}

	/** @test */
	public function it_converts_derived_units()
	{
		$conversor = new UnitsConverter([
			'gf' => 1000.0,
			'N'  => 9.8066,
		]);

		$this->assertEquals(10 / 9.8066, $conversor->kgf(1.0, 'daN'));     // derived to base
		$this->assertEquals(9.8066 / 10, $conversor->daN(1.0, 'kgf'));     // base to derived
		$this->assertEquals(100.0, $conversor->daN(1.0, 'kN'));            // derived to derived
		$this->assertEquals(1.0e+18, $conversor->uN(1.0, 'TN'));           // big number
		$conversor->lbf = 2.2046;                                          // dyn. added unit
		$this->assertEquals(1.0, 1.0e+18 * $conversor->Tlbf(1.0, 'ulbf')); // small number
	}

	/** @test */
	public function it_throws_an_exception_when_unknown_FROM_base()
	{
		$conversor = new UnitsConverter([
			'x' => 1.0,
		]);

		$this->expectExceptionMessage('Unknown Unit: y');
		$conversor->x(1.0, 'y');   // unknown FROM
	}

	/** @test */
	public function it_throws_an_exception_when_unknown_TO_base()
	{
		$conversor = new UnitsConverter([
			'x' => 1.0,
		]);

		$this->expectException(UnitsConverterException::class);
		$this->expectExceptionMessage("Unknown Unit: y");
		$conversor->y(1.0, 'x');   // Unknown TO
	}

	/** @test */
	public function it_throws_an_exception_when_unknown_TO__multiple_base()
	{
		$conversor = new UnitsConverter([
			'x' => 1.0,
		]);

		$this->expectException(UnitsConverterException::class);
		$this->expectExceptionMessage('Unknown Base Unit: y');
		$conversor->ky(1.0, 'x');   // Unknown TO
	}

	/** @test */
	public function it_uses_facades()
	{
		ConverterStarter::start();

		$this->assertEquals(10, FConverter::N(1, 'daN'));

		$this->assertEquals(145.0377439, PConverter::psi(1, 'MPa'));
	}

}