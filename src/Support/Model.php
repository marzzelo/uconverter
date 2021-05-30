<?php


namespace Marzzelo\Uconverter\Support;

abstract class Model
{
	protected array $attributes = [];

	public function __construct(array $attributes = [])
	{
		$this->fill($attributes);
	}

	public function fill(array $attributes = [])
	{
		foreach ($attributes as $name => $value) {
			$this->setAttribute($name, $value);
		}
	}

	public function getAttributes(): array
	{
		return $this->attributes;
	}

	public function getAttribute($name)
	{
		$value = $this->getAttributeValue($name);

		if ($this->hasGetMutator($name)) {
			return $this->mutateAttribute($name, $value);
		}

		return $value;
	}

	protected function hasGetMutator($name): bool
	{
		return method_exists($this, 'get'.Str::studly($name).'Attribute');
	}

	protected function mutateAttribute($name, $value)
	{
		return $this->{'get'.Str::studly($name).'Attribute'}($value);
	}

	public function getAttributeValue($name)
	{
		// printf("\nModel: getAttributeValue($name): " . $this->attributes[$name] ?? "null");
		return $this->attributes[$name] ?? null;
	}

	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
	}

	public function __set($name, $value)
	{
		$this->setAttribute($name, $value);
	}

	public function __get($name)
	{
		return $this->getAttribute($name);
	}

	public function __isset($name)
	{
		return isset($this->attributes[$name]);
	}

	public function __unset($name)
	{
		unset ($this->attributes[$name]);
	}
}
