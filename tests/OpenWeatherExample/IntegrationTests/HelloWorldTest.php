<?php

namespace OpenWeatherTestExample\IntegrationTests;

class HelloWorldTest extends \PHPUnit_Framework_TestCase
{
    const TEST_HOST = '127.0.0.1';
    const TEST_PORT = 8765;
    const TEST_TIMEOUT_SECS = 2;

    public function setUp()
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
            if ($this->canConnectToTestWebServer())
            {
                return;
            }
        }

        throw new \RuntimeException(sprintf('Could not open test web server %s:%d', self::TEST_HOST, self::TEST_PORT));
    }

    /**
     * @return bool
     */
    private function canConnectToTestWebServer()
    {
        $sp = @fsockopen(self::TEST_HOST, self::TEST_PORT);
        if ($sp === false)
        {
            return false;
        }

        fclose($sp);
        return true;
    }

    public function testHelloWorld()
    {
        $res = @file_get_contents('http://127.0.0.1:8765');
        $this->assertEquals('Hello World', $res);
    }
}