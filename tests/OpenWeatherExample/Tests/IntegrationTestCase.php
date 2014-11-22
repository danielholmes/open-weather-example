<?php

namespace OpenWeatherExample\Tests;

use Guzzle\Http\Client;
use Guzzle\Http\ClientInterface;

class IntegrationTestCase extends \PHPUnit_Framework_TestCase
{
    const TEST_HOST = '127.0.0.1';
    const TEST_PORT = 8901;
    const TEST_TIMEOUT_SECS = 2;

    public static function setUpBeforeClass()
    {
        $command = sprintf(
            'php -S %s:%d -t %s >/dev/null 2>&1 & echo $!',
            self::TEST_HOST,
            self::TEST_PORT,
            realpath(__DIR__ . '/../../../web')
        );

        $output = array();
        exec($command, $output);
        $pid = (int) $output[0];

        register_shutdown_function(function() use ($pid)
        {
            exec('kill ' . $pid);
        });

        $start = microtime(true);
        while ((microtime(true) - $start) <= self::TEST_TIMEOUT_SECS)
        {
            if (self::canConnectToTestWebServer())
            {
                return;
            }
        }

        throw new \RuntimeException(sprintf('Could not open test web server %s:%d', self::TEST_HOST, self::TEST_PORT));
    }

    /**
     * @return bool
     */
    private static function canConnectToTestWebServer()
    {
        $socket = @fsockopen(self::TEST_HOST, self::TEST_PORT);
        if ($socket === false)
        {
            return false;
        }

        fclose($socket);
        return true;
    }

    /**
     * @return ClientInterface
     */
    protected function createTestClient()
    {
        return new Client(sprintf('http://%s:%d', self::TEST_HOST, self::TEST_PORT));
    }
}