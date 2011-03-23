<?php

/**
 * behavoir that handles test dependencies
 *
 * run tests in
 *
 * @package Behaviors
 */
class TestCodeCoverageBehavior extends TestRunnerBehaviorAbstract
{
	/**
	 * Called before every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that will be run
	 * @return void
	 */
	public function beforeTest(TestRunnerEvent $event)
	{
		$event->currentTest->results->collectCodeCoverageInformation(true);
		//$event->currentTest->results->collectRawCodeCoverageInformation(true);
	}

	/**
	 * Called after every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that has been run
	 * @return void
	 */
	public function afterTest(TestRunnerEvent $event)
	{

	}






	/**
	 * Called after running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function afterRun(TestRunnerEvent $event)
	{
		$coverage = PHP_CodeCoverage::getInstance();

		//print_r($coverage->getSummary());

		if (extension_loaded('tokenizer') && extension_loaded('xdebug'))
		{
			$reportPath = Yii::getPathOfAlias($this->owner->command->testPath) . '/report';

			if (!file_exists($reportPath)) {
				mkdir($reportPath, 0775, true);
			}

		    if (true)//isset($arguments['coverageClover']))
		    {
		        $this->owner->command->p(
		          "\nWriting code coverage data to XML file, this may take a moment."
		        );

		        require_once 'PHP/CodeCoverage/Report/Clover.php';

		        $writer = new PHP_CodeCoverage_Report_Clover;
		        $writer->process($coverage, $reportPath . '/clover.xml');

		        $this->owner->command->p("\n");
		        unset($writer);
		    }

		    if (true)//isset($arguments['reportDirectory']))
		    {
			    if (!file_exists($reportPath . '/coverage')) {
				    mkdir($reportPath . '/coverage', 0775, true);
			    }

		        $this->owner->command->p(
		          "\nGenerating code coverage report, this may take a moment."
		        );

		        $title = '';

		        if (isset($arguments['configuration'])) {
		            $loggingConfiguration = $arguments['configuration']->getLoggingConfiguration();

		            if (isset($loggingConfiguration['title'])) {
		                $title = $loggingConfiguration['title'];
		            }
		        }

		        require_once 'PHP/CodeCoverage/Report/HTML.php';

		        $writer = new PHP_CodeCoverage_Report_HTML(
		          array(
		            'title'          => 'code coverage',//$title,
		            'charset'        => 'UTF-8',//$arguments['reportCharset'],
/*		            'yui'            => $arguments['reportYUI'],
		            'highlight'      => $arguments['reportHighlight'],
		            'lowUpperBound'  => $arguments['reportLowUpperBound'],
		            'highLowerBound' => $arguments['reportHighLowerBound'],*/
		            'generator'      => ' and PHPUnit ' . PHPUnit_Runner_Version::id()
		          )
		        );

		        $writer->process(
		          $coverage, $reportPath . '/coverage'
		        );

		        $this->owner->command->p("\n");
		        unset($writer);
		    }
		}






	}

}
