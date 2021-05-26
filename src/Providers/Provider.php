<?php


namespace Doba\Uconverter\Providers;

use Doba\Uconverter\Support\Container;

abstract class Provider
{
	protected Container $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	abstract public function register();
}