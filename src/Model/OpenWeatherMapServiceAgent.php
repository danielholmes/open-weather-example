<?php

namespace OpenWeatherExample\Model;

use Guzzle\Http\ClientInterface;

class OpenWeatherMapServiceAgent
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $cityQuery
     * @return array
     */
    public function getCurrentWeather($cityQuery)
    {
        $body = $this->performJsonGet(
            'weather',
            [
                'q' => $cityQuery
            ]
        );

        return array_map(
            function (\stdClass $rawWeather)
            {
                return $rawWeather->main;
            },
            $body->weather
        );
    }

    /**
     * @param string $cityQuery
     * @return CityForecast
     */
    public function getThreeDaysForecast($cityQuery)
    {
        $body = $this->performJsonGet(
            'forecast/daily',
            [
                'q' => $cityQuery,
                'cnt' => 3
            ]
        );

        return new CityForecast(
            $body->city->name,
            $body->city->country,
            array_map(
                function(\stdClass $rawDayForecast)
                {
                    return $this->parseDayForecast($rawDayForecast);
                },
                $body->list
            )
        );
    }

    /**
     * @param string $path
     * @param array $query
     * @return \stdClass
     * @throws OpenWeatherMapAgentException
     */
    private function performJsonGet($path, array $query)
    {
        $fullQuery = array_merge(
            [
                'mode' => 'json',
                'units' => 'metric'
            ],
            $query
        );
        $response = $this->httpClient->get(
            $path,
            null,
            [
                'query' => $fullQuery,
                'exceptions' => false
            ]
        )->send();

        if (!$response->isSuccessful())
        {
            throw new OpenWeatherMapAgentException(sprintf(
                'Unsuccessful request: [%s] %s',
                $response->getStatusCode(),
                $response->getBody(true)
            ));
        }

        if (strpos($response->getContentType(), 'application/json') !== 0)
        {
            throw new OpenWeatherMapAgentException(sprintf(
                'Request returned non-json response: [%s] %s',
                $response->getContentType(),
                $response->getBody(true)
            ));
        }

        $rawBody = $response->getBody(true);
        $body = @json_decode($rawBody);

        if ($body === null || $body === false)
        {
            throw new OpenWeatherMapAgentException(sprintf(
                'Request returned non-json response: %s',
                $response->getBody(true)
            ));
        }

        if ((string) $body->cod !== '200')
        {
            throw new OpenWeatherMapAgentInvalidCityException(sprintf(
                'City: %s',
                $response->getBody(true)
            ));
        }

        return $body;
    }

    /**
     * @param \stdClass $rawDayForecast
     * @return DayForecast
     */
    private function parseDayForecast(\stdClass $rawDayForecast)
    {
        return new DayForecast(
            $rawDayForecast->temp->day,
            $rawDayForecast->temp->min,
            $rawDayForecast->temp->max,
            $rawDayForecast->weather[0]->main
        );
    }
}