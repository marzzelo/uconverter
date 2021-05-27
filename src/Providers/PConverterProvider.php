<?php

namespace Marzzelo\Uconverter\Providers;

use Marzzelo\Uconverter\UnitsConverter;

class PConverterProvider extends Provider
{
	const PSI_PER_MPA = 145.0377439;

	const N_PER_KGF   = 9.806652048217;

	public function register()
	{
		$this->container->bind('pconvert',
			function () {
				return new UnitsConverter([
					'psi'   => self::PSI_PER_MPA,
					'Pa'    => 1e6,
					'gmm2'  => 1000.0 / self::N_PER_KGF,
					'bar'   => 10,
					'atm'   => 9.8692331,
					'mHg'   => 0.7500616773,
					'inHg'  => 295.2998924,
					'inH2O' => 4018.6466907,
					'torr'  => 7500.616773,
				]);
			});
	}
}