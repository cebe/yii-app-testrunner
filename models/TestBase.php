<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 */
class TestBase extends TestAbstract
{
	/**
	 * list of attributes
	 *
	 * these attributes will be fetched from docbock annotations
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		}

		parent::__get($name);
	}

	public function init()
	{
		parent::init();

		$this->parseDocBlock();
	}

	/**
	 * adds all values of docblock comments starting with @ to $this->attributes
	 *
	 * @return void
	 */
	protected function parseDocBlock()
	{
		$lines = explode("\n", $this->docBlock);
		foreach($lines as $line)
		{
			$line = ltrim($line, " \t\n\r\0\x0B*");
			if (!empty($line) AND $line{0} == '@') {
				$key = substr($line, 1, strpos($line, ' ') - 1);
				$value = substr($line, strpos($line, ' ') + 1);
				$this->attributes[$key] = $value;
			}
		}
	}



}
