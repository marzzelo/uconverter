<?php


namespace Marzzelo\Uconverter;

use Marzzelo\Uconverter\Support\Str;
use Marzzelo\Uconverter\Support\Model;
use Marzzelo\Uconverter\Exceptions\UnitsConverterException;

class UnitsConverter extends Model
{
	/**
	 * @throws \Marzzelo\Uconverter\Exceptions\UnitsConverterException
	 */
	public function __call($name, $arguments) // $convert->N(10, 'kgf')
	{
		$this->validateUnitDefinition($arguments[1], $arguments[0]);

		return ($this->$name / $this->{$arguments[1]} * $arguments[0]);
	}

	/**
	 * @throws \Marzzelo\Uconverter\Exceptions\UnitsConverterException
	 */
	public function setAttribute($name, $value)
	{
		$this->validateUnitDefinition($name, $value);
		parent::setAttribute($name, (float)$value);
	}


	/**
	 * @throws \Marzzelo\Uconverter\Exceptions\UnitsConverterException
	 */
	public function getAttribute($name)
	{
		return parent::getAttribute($name) ?? $this->multiple($name);
	}

	/**
	 * @throws \Marzzelo\Uconverter\Exceptions\UnitsConverterException
	 */
	private function multiple($name): ?float
	{
		$roots = [
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
		];

		foreach ($roots as $root => $factor) {

			// if (str_starts_with($name, $root)) {  // PHP 8

			if (Str::beginsWith($name, $root)) {

				$base_unit = substr($name, strlen($root));

				if (empty($base_unit)) {
					throw new UnitsConverterException("Unknown Unit: $name (k)");
				}

				$base_size = parent::getAttribute($base_unit);

				if (is_null($base_size)) {
					throw new UnitsConverterException("Unknown Base Unit: $base_unit");
				}
				return ($base_size / $factor);
			}
		}

		throw new UnitsConverterException("Unknown Unit: $name");
	}

	/**
	 * @param $name
	 * @param $value
	 * @throws \Marzzelo\Uconverter\Exceptions\UnitsConverterException
	 */
	private function validateUnitDefinition($name, $value): void
	{
		if (is_numeric($name)) {
			throw new UnitsConverterException('The unit name must be alphabetic');
		}
		if (!is_numeric($value)) {
			throw new UnitsConverterException("The [$name] value must be numeric");
		}
		if ($value <= 0) {
			throw new UnitsConverterException("The [$name] value must be greater than zero");
		}
	}

	public static function getConverter($name): UnitsConverter
	{
		$table = [
			'pressure' => [
				'psi'   => 145.0377439,
				'Pa'    => 1e6,
				'gmm2'  => 1000.0 / 9.806652048217,
				'bar'   => 10,
				'atm'   => 9.8692331,
				'mHg'   => 0.7500616773,
				'inHg'  => 295.2998924,
				'inH2O' => 4018.6466907,
				'torr'  => 7500.616773,
			],
			'force'    => [
				'N'      => 9.806652048217,
				'lbf'    => 2.2046231,
				'gf'     => 1000.0,
				'ouncef' => 35.2739694,
				'poundf' => 2.2046231,
				'dyne'   => 9.806652048217 * 1e5,
				'sthene' => 9.806652048217 * 1e-3,
			],
		];

		return new UnitsConverter($table[$name]);
	}


}