<?php

namespace OpenWeatherExample\Model;

class CityForecast
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $country;

    /**
     * @var array
     */
    private $dayForecasts;

    /**
     * @param string $name
     * @param string $country
     * @param array $dayForecasts
     */
    public function __construct($name, $country, array $dayForecasts)
    {
        $this->name = $name;
        $this->country = $country;
        $this->dayForecasts = $dayForecasts;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getDayForecasts()
    {
        return $this->dayForecasts;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}