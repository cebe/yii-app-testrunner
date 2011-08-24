<?php
/*
	This is an example config file for your yii console


*/

return array(
	'commandMap' => array(
        'tests' => array(
			/*
			 * this is the command class of testrunner
			 * you have to change it if you extended TestrunnerCommand
			 */
            'class' => 'application.commands.testRunner.TestrunnerCommand',
			/*
			 * here you can define all properties of the testRunner even which class to use as testrunner
			 */
            'testRunner' => array(
				/*
				 * Yii alias of the class to use as testrunner
				 * if you give a name only, make shure it can be accessed without autoloading or
				 * already is accessable by include_path
				 */
				'class' => 'TestRunner',
				/*
				 * attach behaviors(extensions) to testRunner class
				 */
                'behaviors' => array(
                    'TestDefaultOutputBehavior',
                    'junit' => array(
                        'class' => 'testRunner.extensions.loggerJUnit.TestLoggerJUnitBehavior',
                        'logPath' => dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'build/logs',
                    ),
/*					'todos' => array(
                        'class' => 'testRunner.extensions.todoDetector.TestTodoDetectorBehavior'
                    ),*/
                    'dependencies' => array(
                        'class' => 'testRunner.extensions.testDependencies.TestDependencyBehavior',
                    ),
                    'codeCoverage' => array(
                        'class' => 'testRunner.extensions.codeCoverage.TestCodeCoverageBehavior',
                        'cloverXml' => true,
                        'cloverXmlPath' => dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'build/logs',
                        'cloverXmlFile' => 'clover.xml',
                        'coverageHtml' => true,
                        'coverageHtmlPath' => dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'build/coverage',
                        'coverageHtmlConfig' => array(
                            'title'          => 'Unit Test Code Coverage HTML',
                            'charset'        => 'UTF-8',
                            'yui'            => true,
                            'highlight'      => false,
                            'lowUpperBound'  => 35,
                            'highLowerBound' => 70,
                        ),
                    ),
                ),
            ),
            'testCollector' => array(
                'behaviors' => array(
                    'phpunit' => array(
                        'class' => 'testRunner.extensions.phpunit.TestCollectorPHPUnit',
                    ),
                ),
            ),
        ),
	),
);
