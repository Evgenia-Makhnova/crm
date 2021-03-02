<?php

/**
 * PHP version 5.4
 *
 * API client test class
 *
 * @category RetailCrm
 * @package  RetailCrm
 */

namespace RetailCrm\Tests;

use RetailCrm\Test\TestCase;

/**
 * Class ApiClientTest
 *
 * @category RetailCrm
 * @package  RetailCrm
 */
class ApiClientTest extends TestCase
{
    /**
     * @group client
     */
    public function testConstruct()
    {
        $client = static::getApiClient();

        static::assertInstanceOf('RetailCrm\ApiClient', $client);
    }
}