<?php

use BrianLangevin\CountryCodeConverters\StateCodeConverter;
use PHPUnit\Framework\TestCase;

/**
 * @covers StateCodeConverter
 */
class StateCodeConverterTest extends TestCase
{
	protected $instance;

	protected $mappings = [
		'Alabama' => 'AL',
		'Alaska' => 'AK',
	];

	public function setUp()
	{
		parent::setUp();

		$this->instance = new StateCodeConverter($this->mappings);
	}

	/** @test */
	public function state_names_can_be_converted_to_state_codes()
	{
		$this->assertEquals(
			'AL',
			$this->instance->getCode('Alabama')
		);

		$this->assertEquals(
			'AK',
			$this->instance->getCode('Alaska')
		);
	}

	/** @test */
	public function state_codes_can_be_converted_to_state_names()
	{
		$this->assertEquals(
			'Alabama',
			$this->instance->getName('AL')
		);

		$this->assertEquals(
			'Alaska',
			$this->instance->getName('AK')
		);
	}

	/** @test */
	public function state_code_converter_automatically_loads_countries_config_file()
	{
		$instance = new StateCodeConverter();

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
