<?php


namespace Doba\Uconverter;

use Doba\Uconverter\Support\Model;
use Doba\Uconverter\Exceptions\UnitsConverterException;

class UnitsConverter extends Model
{
	public function __call($name, $arguments) // $convert->N(10, 'kgf')
	{
		return (($this->$name != null) && ($this->{$arguments[1]} != null))
			? ($this->$name / $this->{$arguments[1]} * $arguments[0])
			: null;
	}

	/**
	 * @throws \Doba\Uconverter\Exceptions\UnitsConverterException
	 */
	public function getAttribute($name)
	{
		return parent::getAttribute($name) ?? $this->multiple($name);
	}

	/**
	 * @throws \Doba\Uconverter\Exceptions\UnitsConverterException
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
			if (str_starts_with($name, $root)) {
				$base_unit = substr($name, strlen($root));
				$base_size = parent::getAttribute($base_unit);
				if (is_null($base_size)) {
					throw new UnitsConverterException("Unknown Base Unit: $base_unit");
				}
				return ($base_size / $factor);
			}
		}
		throw new UnitsConverterException("Unknown Unit: $name");
	}


}