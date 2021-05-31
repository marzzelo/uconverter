<?php

namespace Marzzelo\Tests;

use PHPUnit\Framework\TestCase;
use Marzzelo\Uconverter\UnitsConverter;
use Marzzelo\Uconverter\Exceptions\UnitsConverterException;

class UnitsConverterTest extends TestCase
{
	const THE_KGF_VALUE_MUST_BE_NUMERIC           = 'The [kgf] value must be numeric';

	const THE_KGF_VALUE_MUST_BE_GREATER_THAN_ZERO = 'The [kgf] value must be greater than zero';

	const UNKNOWN_UNIT_Y                          = 'Unknown Unit: y';

	const UNKNOWN_BASE_UNIT_Y                     = 'Unknown Base Unit: y';

	const NEWTONS_PER_KGF                         = 9.806652048217;

	const CM_PER_INCH                             = 2.5400000025908;

	const LBF_PER_KGF                                       = 2.2046;

	/** @test */
	public function assigning_alpha_value_throws_exception()
	{
		$converter = new UnitsConverter;
		$this->expectException(UnitsConverterException::class);
		$this->expectExceptionMessage(self::THE_KGF_VALUE_MUST_BE_NUMERIC);

		$converter->kgf = 'x';
	}

	/** @test */
	public function assigning_zero_value_throws_exception()
	{
		$converter = new UnitsConverter;
		$this->expectException(UnitsConverterException::class);
		$this->expectExceptionMessage(self::THE_KGF_VALUE_MUST_BE_GREATER_THAN_ZERO);

		$converter->kgf = 0;
	}

	/** @test */
	public function assigning_zero_value_in_the_constructor_throws_exception()
	{
		$this->expectException(UnitsConverterException::class);
		$this->expectExceptionMessage(self::THE_KGF_VALUE_MUST_BE_GREATER_THAN_ZERO);

		$converter = new UnitsConverter([
			'kgf' => 0,
		]);
	}

	/** @test */
	public function it_creates_a_property_dinamically()
	{
		$converter = new UnitsConverter([
			'kgf' => 1,
			'N'   => self::NEWTONS_PER_KGF,
		]);

		$this->assertEquals(1, $converter->kgf);

		$converter->lbf = self::LBF_PER_KGF;
		$this->assertEquals(self::LBF_PER_KGF, $converter->lbf);
		$this->assertEquals(1.0, $converter->kgf(self::LBF_PER_KGF, 'lbf'));

		$this->assertEquals(1000, $converter->lbf(1, 'klbf'));
		$this->assertEquals(1000.0, $converter->kgf(self::LBF_PER_KGF, 'klbf'));
	}

	/** @test */
	public function it_converts_base_units()
	{
		$converter = new UnitsConverter([
			'gf' => 1000.0,
			'N'  => self::NEWTONS_PER_KGF,
		]);

		$this->assertEquals(10 * self::NEWTONS_PER_KGF, $converter->N(10.0, 'kgf'));
		$this->assertEquals(10.0 / self::NEWTONS_PER_KGF, $converter->kgf(10.0, 'N'));
	}

	/** @test */
	public function it_converts_derived_units()
	{
		$converter = new UnitsConverter([
			'gf' => 1000.0,
			'N'  => self::NEWTONS_PER_KGF,
		]);

		$this->assertEquals(10 / self::NEWTONS_PER_KGF, $converter->kgf(1.0, 'daN'));     // derived to base
		$this->assertEquals(self::NEWTONS_PER_KGF / 10, $converter->daN(1.0, 'kgf'));     // base to derived
		$this->assertEquals(100.0, $converter->daN(1.0, 'kN'));                           // derived to derived
		$this->assertEquals(1.0e+18, $converter->uN(1.0, 'TN'));                          // big number
		$converter->lbf = self::LBF_PER_KGF;                                                        // dyn. added unit
		$this->assertEquals(1.0, 1.0e+18 * $converter->Tlbf(1.0, 'ulbf'));                // small number
	}

	/** @test */
	public function it_throws_an_exception_when_unknown_FROM_base()
	{
		$converter = new UnitsConverter([
			'x' => 1.0,
		]);

		$this->expectExceptionMessage(self::UNKNOWN_UNIT_Y);
		$converter->x(1.0, 'y');   // unknown FROM
	}

	/** @test */
	public function it_throws_an_exception_when_unknown_TO_base()
	{
		$converter = new UnitsConverter([
			'x' => 1.0,
		]);

		$this->expectException(UnitsConverterException::class);
		$this->expectExceptionMessage(self::UNKNOWN_UNIT_Y);
		$converter->y(1.0, 'x');   // Unknown TO
	}

	/** @test */
	public function it_throws_an_exception_when_unknown_TO__multiple_base()
	{
		$converter = new UnitsConverter([
			'x' => 1.0,
		]);

		$this->expectException(UnitsConverterException::class);
		$this->expectExceptionMessage(self::UNKNOWN_BASE_UNIT_Y);
		$converter->ky(1.0, 'x');   // Unknown TO
	}


	/** @test */
	public function it_gets_included_converters()
	{
		$fconversor = UnitsConverter::getConverter('force');
		$pconversor = UnitsConverter::getConverter('pressure');
		$lconversor = UnitsConverter::getConverter('length');

		$this->assertEquals(self::NEWTONS_PER_KGF, $fconversor->N(1, 'kgf'));
		$this->assertEquals(1e6, $pconversor->Pa(1, 'MPa'));
		$this->assertEquals(self::CM_PER_INCH, $lconversor->cm(1, 'in'));
	}

}