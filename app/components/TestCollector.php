<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 */
class TestCollector extends CComponent
{
	/**
	 * base Path for tests
	 *
	 * @var null|string
	 */
	private $_basePath = null;

	/**
	 * sets the base path for tests
	 *
	 * @param  $path
	 * @return void
	 */
	public function setBasePath($path)
	{
		$this->_basePath = $path;
	}

	/**
	 * gets the base path for tests
	 *
	 * default is application.tests
	 *
	 * @return string
	 */
	public function getBasePath()
	{
		if (is_null($this->_basePath)) {
			$this->_basePath = dirname(Yii::app()->basePath) . DS . 'tests';
		}
		return $this->_basePath;
	}

	public function collectTests()
	{

		return new TestCollection();
	}

	public function collectTestsByPath()
	{

		return new TestCollection();
	}

	public function collectTestsByTag()
	{

		return new TestCollection();
	}

	public function collectTestsByWheaterAndTemper()
	{

		return new TestCollection();
	}
}
