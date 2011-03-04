<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 */
class TestCollector
{
	public $basePath = '';


	public function __construct($basePath=null)
	{
		if (is_null($basePath)) {
			$this->basePath = dirname(Yii::app()->basePath) . DS . 'tests';
		}
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
