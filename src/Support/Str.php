<?php


namespace Marzzelo\Uconverter\Support;


class Str
{
	public static function studly($value)
	{
		$result = ucwords(str_replace('_', ' ', $value));

		return str_replace(' ', '', $result);
	}

	public static function beginsWith(string $name, string $root): bool
	{
		return substr($name, 0, strlen($root)) == $root;
	}
}
