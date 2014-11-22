<?php

namespace OpenWeatherExample\Web;

use Guzzle\Plugin\Backoff\BackoffPlugin;
use Guzzle\Service\Client;
use OpenWeatherExample\Model\OpenWeatherMapAgentException;
use OpenWeatherExample\Model\OpenWeatherMapAgentInvalidCityException;
use OpenWeatherExample\Model\OpenWeatherMapServiceAgent;

class FrontController
{
    /**
     * @param array $queryParams
     */
    public function run(array $queryParams)
    {
        if (isset($queryParams['location']))
        {
            $this->runSearch($queryParams['location']);
            return;
        }

        $this->runEnter();
    }

    /**
     * @param string $location
     */
    private function runSearch($location)
    {
        if (!$this->isValidLocation($location))
        {
            $this->invalidLocationResponse($location);
            return;
        }

        $agent = $this->createServiceAgent();
        try
        {
            $this->includeTemplate('results.php', [
                'current' => $agent->getCurrentWeather($location),
                'forecast' => $agent->getThreeDaysForecast($location)
            ]);
        }
        catch (OpenWeatherMapAgentException $e)
        {
            $this->serviceUnavailableResponse($location);
        }
        catch (OpenWeatherMapAgentInvalidCityException $e)
        {
            $this->invalidLocationResponse($location);
        }
    }

    /**
     * @param string $location
     * @return boolean
     */
    private function isValidLocation($location)
    {
        return !in_array($location, [null, ''], true);
    }

    /**
     * @param string $location
     */
    private function invalidLocationResponse($location)
    {
        http_response_code(400);
        $this->runEnter($location, sprintf('The location you entered "%s" could not be found', $location));
    }

    /**
     * @param string $location
     */
    private function serviceUnavailableResponse($location)
    {
        http_response_code(503);
        $this->runEnter($location, 'The service it temporarily unavailable, please try again soon');
    }

    /**
     * @param string $defaultLocation
     * @param string $errorMessage
     */
    private function runEnter($defaultLocation = '', $errorMessage = null)
    {
        $this->includeTemplate('enter.php', [
            'errorMessage' => $errorMessage,
            'location' => $defaultLocation
        ]);
    }

    /**
     * @param string $templateName
     * @param array $vars
     */
    private function includeTemplate($templateName, array $vars)
    {
        extract($vars);
        include(__DIR__ . '/../../templates/' . $templateName);
    }

    /**
     * @return OpenWeatherMapServiceAgent
     */
    private function createServiceAgent()
    {
        $client = new Client('http://api.openweathermap.org/data/2.5/');
        $backoffPlugin = BackoffPlugin::getExponentialBackoff();
        $client->addSubscriber($backoffPlugin);
        return new OpenWeatherMapServiceAgent($client);
    }
}