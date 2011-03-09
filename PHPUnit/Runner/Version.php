<?php
/**
 * This Class exists to work around the problem that in git checkedout code of phpunit
 * the version is @package_version@ which Yii will interpret as < 3.5.0RC1 and include wrong files.
 */

class PHPUnit_Runner_Version {


	/**
     * Returns the current version of PHPUnit.
     *
     * @return string
     */
    public static function id()
    {
        return '3.5.13';
    }

    /**
     * @return string
     */
    public static function getVersionString()
    {
        return 'PHPUnit 3.5.13 by Sebastian Bergmann.';
    }
}
