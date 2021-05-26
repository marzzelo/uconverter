<?php


namespace Marzzelo\Uconverter\Providers;

use Marzzelo\Uconverter\UnitsConverter;

class FConverterProvider extends Provider
{
	const LBF_PER_KGF = 2.2046231;

	const N_PER_KGF   = 9.806652048217;

	public function register()
	{
		$this->container->bind('fconvert',
			function () {
				return new UnitsConverter([
					'N'      => self::N_PER_KGF,
					'lbf'    => self::LBF_PER_KGF,
					'gf'     => 1000.0,
					'ouncef' => 35.2739694,
					'poundf' => self::LBF_PER_KGF,
					'dyne'   => self::N_PER_KGF * 1e5,
					'sthene' => self::N_PER_KGF * 1e-3,
				]);
			});
	}
}