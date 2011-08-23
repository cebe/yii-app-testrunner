<?php

/**
 * Event that is raised from testCollector
 *
 * @link http://www.yiiframework.com/doc/guide/1.1/en/basics.component#component-event
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestCollector
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

