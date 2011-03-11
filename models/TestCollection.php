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
		$this->tests = $this->quickSort($lowerEqual, $this->tests);
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
