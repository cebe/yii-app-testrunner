<?php

/**
 * abstract class for testRunners application components
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Components
 */
class TestComponentAbstract extends CApplicationComponent
{
	/**
	 * the correcsponding command
	 *
	 * @var TestrunnerCommand
	 */
	public $command = null;

	/**
	 *
	 * @param null|TestrunnerCommand $command
	 */
	public function __construct($command=null)
	{
		$this->command = $command;
	}

	/**
	 * Configures the class with the specified configuration.
	 * @param array $config the configuration array
	 */
	public function configure($config)
	{
		if (is_array($config))
		{
			foreach($config as $key => $value) {
				$this->$key = $value;
			}
		}
	}

}
