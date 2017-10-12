<?php

namespace BrianLangevin\CountryCodeConverters;

use BadMethodCallException;
use Closure;

class CodeConverter
{
	/**
	 * An array of name to code mappings
	 */
	private $mappings;

	/**
	 * CodeConverter constructor.
	 *
	 * @param array|null $mappings
	 * @throws MissingConfigFile
	 */
	public function __construct(array $mappings = null)
	{
		if (! is_null($mappings)) {
			$this->mappings = $mappings;
			return;
		}

		if (! isset($this->config)) {
			throw new MissingConfigFile;
		}

		if (file_exists($this->config)) {
			$this->mappings = include $this->config;
			return;
		}

		$sep = DIRECTORY_SEPARATOR;
		$configPath = dirname(dirname(__FILE__)) . $sep . 'config' . $sep;
		if (file_exists($configPath . $this->config)) {
			$this->mappings = include $configPath . $this->config;
			return;
		}

		throw new MissingConfigFile;
	}

	/**
	 * Return the code for the given name
	 *
	 * @param $value
	 * @return mixed
	 */
	public function getCode($value)
	{
		return self::first(
			$this->mappings,
			function ($code, $name) use ($value)
			{
				return strtolower($name) == strtolower($value);
			},
			$value
		);
	}

	/**
	 * Return the name for the given code
	 *
	 * @param $value
	 * @return mixed
	 */
	public function getName($value)
	{
		return self::first(
			array_flip($this->mappings),
			function ($name, $code) use ($value)
			{
				return strtolower($code) == strtolower($value);
			},
			$value
		);
	}

	/**
	 * Return the first element in an array passing a given truth test.
	 *
	 * @author Taylor Otwell <taylor@laravel.com>
	 * @license MIT
	 * @see https://laravel.com/docs/5.5/collections#method-first Documentation for this method
	 *
	 * @param  array  $array
	 * @param  callable|null  $callback
	 * @param  mixed  $default
	 * @return mixed
	 */
	private static function first($array, callable $callback, $default = null)
	{
		if (is_null($callback)) {
			if (empty($array)) {
				return self::value($default);
			}

			foreach ($array as $item) {
				return $item;
			}
		}

		foreach ($array as $key => $value) {
			if (call_user_func($callback, $value, $key)) {
				return $value;
			}
		}

		return self::value($default);
	}

	/**
	 * Return the default value of the given value.
	 *
	 * @author Taylor Otwell <taylor@laravel.com>
	 * @license MIT
	 *
	 * @param  mixed  $value
	 * @return mixed
	 */
	private static function value($value)
	{
		return $value instanceof Closure ? $value() : $value;
	}
}