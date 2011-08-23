<?php

/**
 * Default implementation of a test class
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 *
 * @property-read PHPUnit_Framework_TestResult results
 */
abstract class TestBase extends TestAbstract
{

	/**
	 * initialize the test object
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

	}

	/**
	 * return the name of this test
	 *
	 * @return string
	 */
	public function getName()
	{
		// test have to be unique, so make shure to add a prefix or something
		return parent::getName();
	}

}
