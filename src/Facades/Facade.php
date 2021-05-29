<?php

namespace Marzzelo\Uconverter\Facades;

use Exception;
use Marzzelo\Uconverter\Support\Container;

abstract class Facade
{

	protected static ?Container $container = null;

	public static function setContainer(Container $container)
	{
		static::$container = $container;
	}

	public static function getContainer(): ?Container
	{
		return static::$container;
	}

	/**
	 * @throws \Exception
	 */
	public static function getAccessor()
	{
		throw new Exception('Please define the getAccessor method in your facade');
	}

	/**
	 * @throws \ReflectionException
	 */
	public static function getInstance()
	{
		return static::getContainer()
		             ->make(static::getAccessor());
	}

	/**
	 * @throws \ReflectionException
	 */
	public static function __callStatic($method, $args)
	{
		$object = static::getInstance();
		call_user_func_array([$object, $method], $args);
	}
}