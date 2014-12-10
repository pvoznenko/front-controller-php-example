<?php
namespace Tests;

/**
 * Class PreTest
 * @package tests
 *
 * Pre test to test if environment is correct
 */
class PreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The minimum version of PHP required for this project
     * @var string
     */
    const PHP_VERSION_REQUIRED = '5.4.0';

    /**
     * Check php version
     */
    public function testPhpVersion()
    {
        $isRightVersion = version_compare(PHP_VERSION, self::PHP_VERSION_REQUIRED) >= 0;
        $this->assertTrue($isRightVersion, 'PHP 5.4+ is required');
    }

    /**
     * Check if curl is enabled
     */
    public function testCurl()
    {
        $this->assertTrue(function_exists('curl_version'), 'cURL is not enabled. Enable it or be sure to use the Stream request adapter instead');
    }
} 