<?php

/**
 * The test collection represents a collection of tests
 *
 * @todo better description
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestCollector
 */
class TestCollection extends TestCollectionAbstract
{
	public function orderTests($lowerEqual)
	{
		// work around the xdebug.max_nesting_level (default is 100) to work with deep recursion
		$xedebugNesting = ini_get('xdebug.max_nesting_level');
		ini_set('xdebug.max_nesting_level', count($this->tests) + $xedebugNesting);

		$this->tests = $this->quickSort($lowerEqual, $this->tests);

		ini_set('xdebug.max_nesting_level', $xedebugNesting);

		$this->rewind();
	}

	private function quickSort($lowerEqual, $data)
	{
		if (empty($data)) {
			return array();
		}

		$pivot = array_shift($data);

		// filter data that's lower or equal to pivot
		$leqData = array_filter($data, function($elem) use ($pivot, $lowerEqual) {
			return $lowerEqual($elem, $pivot);
		});
		// filter data that's greater than pivot
		$gtData  = array_filter($data, function($elem) use ($pivot, $lowerEqual) {
			return !$lowerEqual($elem, $pivot);
		});

		// reorder lists
		return array_merge($this->quickSort($lowerEqual, $leqData), array($pivot), $this->quickSort($lowerEqual, $gtData));
	}

}
