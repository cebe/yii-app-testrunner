<?php

/**
 *
 */
class TestCollectorEvent extends CEvent
{
	/**
	 * @var string|null
	 */
	public $testPath = null;

	/**
	 * @var array
	 */
	public $descriptions = array();

	/**
	 * @var TestCollectionAbstract|null
	 */
	public $collection = null;
}

