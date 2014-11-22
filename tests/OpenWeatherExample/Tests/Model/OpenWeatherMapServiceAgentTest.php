<?php

namespace OpenWeatherExample\Tests\Model;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use OpenWeatherExample\Model\CityForecast;
use OpenWeatherExample\Model\DayForecast;
use OpenWeatherExample\Model\OpenWeatherAgentException;
use OpenWeatherExample\Model\OpenWeatherMapServiceAgent;

class OpenWeatherMapServiceAgentTest extends \PHPUnit_Framework_TestCase
{
    public function testParsesGetCurrentResponseCorrectly()
    {
        $agent = $this->createCannedResponseAgent(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                file_get_contents(__DIR__ . '/weather-clouds-fog.json')
            )
        );

        $weather = $agent->getCurrentWeather('Sydney AU');

        $this->assertEquals(array('Clouds', 'Fog'), $weather);
    }

    public function testParsesGetForecastResponseCorrectly()
    {
        $agent = $this->createCannedResponseAgent(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                file_get_contents(__DIR__ . '/forecast-daily-example.json')
            )
        );

        $forecast = $agent->getThreeDaysForecast('Sydney AU');

        $this->assertEquals(
            new CityForecast(
                'Sydney',
                'Australia',
                [
                    new DayForecast(25, 21.65, 26.7, 'Clear'),
                    new DayForecast(29.25, 20.52, 34.01, 'Rain'),
                    new DayForecast(28.13, 22.14, 30.3, 'Rain')
                ]
            ),
            $forecast
        );
    }

    /**
     * @dataProvider invalidResponsesDataProvider
     * @expectedException \OpenWeatherExample\Model\OpenWeatherMapAgentException
     * @param Response $response
     */
    public function testHandlesInvalidWeatherResponseCorrectly(Response $response)
    {
        $agent = $this->createCannedResponseAgent($response);

        $agent->getCurrentWeather('Sydney AU');
    }

    /**
     * @dataProvider invalidResponsesDataProvider
     * @expectedException \OpenWeatherExample\Model\OpenWeatherMapAgentException
     * @param Response $response
     */
    public function testHandlesInvalidForecastResponseCorrectly(Response $response)
    {
        $agent = $this->createCannedResponseAgent($response);

        $agent->getThreeDaysForecast('Sydney AU');
    }

    public function testLiveGetForecastCall()
    {
        $forecast = $this->createLiveAgent()->getThreeDaysForecast('Sydney AU');

        $this->assertSame('Sydney', $forecast->getName());
        $this->assertSame('Australia', $forecast->getCountry());
        $days = $forecast->getDayForecasts();
        $this->assertSame(3, count($days));
        // Check we're getting some valid looking data back
        foreach ($days as $day)
        {
            $temperatureReadings = [$day->getTemperature(), $day->getMinTemperature(), $day->getMaxTemperature()];
            foreach ($temperatureReadings as $temperateReading)
            {
                $this->assertGreaterThan(-20, $temperateReading);
                $this->assertLessThan(60, $temperateReading);
            }
        }
    }

    public function testLiveGetCurrentCall()
    {
        $weatherDescriptions = $this->createLiveAgent()->getCurrentWeather('Sydney AU');

        $this->assertInternalType('array', $weatherDescriptions);
        $this->assertGreaterThanOrEqual(1, count($weatherDescriptions));
    }

    /**
     * @expectedException \OpenWeatherExample\Model\OpenWeatherMapAgentInvalidCityException
     */
    public function testLiveGetCurrentWithInvalidCityCall()
    {
        $this->createLiveAgent()->getCurrentWeather('Blah Blah ZZ');
    }

    /**
     * @return array
     */
    public function invalidResponsesDataProvider()
    {
        return [
            [new Response(400, ['Content-Type' => 'application/json'], '"Invalid input"')],
            [new Response(200, ['Content-Type' => 'text/html'], '<p>Some html returned for some reason</p>')],
            [new Response(200, ['Content-Type' => 'application/json'], '<p>Html even though says its json</p>')],
            [new Response(500, ['Content-Type' => 'application/json'], 'Internal server error')]
        ];
    }

    /**
     * @return OpenWeatherMapServiceAgent
     */
    private function createLiveAgent()
    {
        return new OpenWeatherMapServiceAgent(
            new Client('http://api.openweathermap.org/data/2.5/')
        );
    }

    /**
     * @param Response $response
     * @return OpenWeatherMapServiceAgent
     */
    private function createCannedResponseAgent(Response $response)
    {
        $plugin = new MockPlugin();
        $plugin->addResponse($response);

        $client = new Client();
        $client->addSubscriber($plugin);

        return new OpenWeatherMapServiceAgent($client);
    }
}