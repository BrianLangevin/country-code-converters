<?php

use BrianLangevin\CountryCodeConverters\CountryCodeConverter;
use PHPUnit\Framework\TestCase;

/**
 * @covers CountryCodeConverter
 */
class CountryCodeConverterTest extends TestCase
{
	protected $instance;

	protected $mappings = [
		'Canada' => 'CA',
		'The United States' => 'US',
		'United States' => 'US',
	];

	public function setUp()
	{
		parent::setUp();

		$this->instance = new CountryCodeConverter($this->mappings);
	}

	/** @test */
	public function country_names_can_be_converted_to_country_codes()
	{
		$this->assertEquals(
			'CA',
			$this->instance->getCode('Canada')
		);

		$this->assertEquals(
			'US',
			$this->instance->getCode('The United States')
		);

		$this->assertEquals(
			'US',
			$this->instance->getCode('United States')
		);
	}

	/** @test */
	public function country_codes_can_be_converted_to_country_names()
	{
		$this->assertEquals(
			'Canada',
			$this->instance->getName('CA')
		);

		$this->assertEquals(
			'United States',
			$this->instance->getName('US')
		);
	}

	/** @test */
	public function country_code_converter_automatically_loads_countries_config_file()
	{
		$instance = new CountryCodeConverter();

		$sep = DIRECTORY_SEPARATOR;
		$configPath = dirname(dirname(__FILE__)) . $sep . 'config' . $sep;
		$mappings = include $configPath . $instance->config;

		$keys = array_keys($mappings);
		$this->assertEquals(
			$mappings[$keys[0]],
			$instance->getCode($keys[0])
		);
	}
}
