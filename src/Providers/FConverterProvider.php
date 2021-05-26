<?php


namespace Doba\Uconverter\Providers;

use Doba\Uconverter\UnitsConverter;

class FConverterProvider extends Provider
{
	const LBF_PER_KGF = 2.2046231;

	const N_PER_KGF   = 9.81;

	public function register()
	{
		$this->container->bind('fconvert',
			function () {
				return new UnitsConverter([
					'N'   => self::N_PER_KGF,
					'lbf' => self::LBF_PER_KGF,
					'gf'  => 1000.0,
				]);
			});
	}
}