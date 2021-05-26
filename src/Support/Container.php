<?php

namespace Doba\Uconverter\Support;

use Closure;
use ReflectionClass;
use ReflectionException;
use InvalidArgumentException;
use Doba\Uconverter\Exceptions\ContainerException;

class Container
{
	protected static ?Container $instance = null;

	protected array $shared = [];

	protected array $bindings = [];

	protected static bool $verbose = false;

	public static function setInstance(Container $container)
	{
		static::$instance = $container;
	}

	public static function getInstance(bool $verbose = false): ?Container
	{
		self::$verbose = $verbose;

		if (static::$instance == null) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	public function bind($name, $resolver, $shared = false)
	{
		if (self::$verbose) print "[bind(name = $name, resolver = " . json_encode($resolver) . ", shared = $shared)]\n";
		$this->bindings[$name] = [
			'resolver' => $resolver,
			'shared'   => $shared,
		];
	}

	public function instance($name, $object)
	{
		$this->shared[$name] = $object;
	}

	public function singleton($name, $resolver)
	{
		$this->bind($name, $resolver, true);
	}

	/**
	 * @throws \Doba\Exceptions\ContainerException|\ReflectionException
	 */
	public function make($name, array $arguments = [])
	{
		if (self::$verbose) print "[make(name = $name, arguments = " . json_encode($arguments) . ")]\n";
		if (isset ($this->shared[$name])) {
			return $this->shared[$name];
		}

		if (isset ($this->bindings[$name])) {
			$resolver = $this->bindings[$name]['resolver'];
			$shared = $this->bindings[$name]['shared'];
		} else {
			$resolver = $name;
			$shared = false;
		}

		if ($resolver instanceof Closure) {
			$object = $resolver($this);
		} else {
			$object = $this->build($resolver, $arguments);
		}

		if ($shared) {
			$this->shared[$name] = $object;
		}

		return $object;
	}

	/**
	 * @throws \ReflectionException
	 * @throws \Doba\Exceptions\ContainerException
	 */
	public function build($name, array $arguments = [])
	{
		if (self::$verbose) print "[build(name = $name, args = " . json_encode($arguments) . ")]\n";

		$reflection = new ReflectionClass($name);

		if (!$reflection->isInstantiable()) {
			throw new InvalidArgumentException("$name is not instantiable");
		}

		$constructor = $reflection->getConstructor(); //ReflectionMethod

		if (is_null($constructor)) {
			return new $name;
		}

		$constructorParameters = $constructor->getParameters(); //[ReflectionParameter]

		$dependencies = [];

		foreach ($constructorParameters as $constructorParameter) {

			$parameterName = $constructorParameter->getName();

			if (isset ($arguments[$parameterName])) {
				$dependencies[] = $arguments[$parameterName];
				continue;
			}

			try {
				$parameterClass = $constructorParameter->getType();
			} catch (ReflectionException $e) {
				throw new ContainerException("Unable to build [$name]: " . $e->getMessage(), null, $e);
			}

			if ($parameterClass != null) {
				$parameterClassName = $parameterClass->getName();
				$dependencies[] = $this->make($parameterClassName);
			} else {
				throw new ContainerException("Please provide the value of the parameter [$parameterName]");
			}
		}

		return $reflection->newInstanceArgs($dependencies);
	}

}