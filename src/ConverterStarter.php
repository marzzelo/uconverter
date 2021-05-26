<?php


namespace Marzzelo\Uconverter;

use Marzzelo\Uconverter\Facades\Facade;
use Marzzelo\Uconverter\Support\Container;
use Marzzelo\Uconverter\Providers\FConverterProvider;
use Marzzelo\Uconverter\Providers\PConverterProvider;

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