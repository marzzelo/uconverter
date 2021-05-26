<?php


namespace Doba\Uconverter;

use Doba\Uconverter\Facades\Facade;
use Doba\Uconverter\Support\Container;
use Doba\Uconverter\Providers\FConverterProvider;
use Doba\Uconverter\Providers\PConverterProvider;

class ConverterStarter
{
	public static function start()
	{
		$container = Container::getInstance();
		Facade::setContainer($container);

		(new FConverterProvider($container))->register();
		(new PConverterProvider($container))->register();
	}

}