<?php

namespace Doba\Uconverter\Facades;

use Exception;
use Doba\Uconverter\Support\Container;

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
		return match (count($args)) {
			0 => $object->$method(),
			1 => $object->$method($args[0]),
			2 => $object->$method($args[0], $args[1]),
			3 => $object->$method($args[0], $args[1], $args[2]),
			default => call_user_func_array([$object, $method], $args),
		};
	}
}