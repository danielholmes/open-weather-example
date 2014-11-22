<?php

namespace OpenWeatherExample\Model;

class DayForecast
{
    /**
     * @var float
     */
    private $temperature;

    /**
     * @var float
     */
    private $minTemperature;

    /**
     * @var float
     */
    private $maxTemperature;

    /**
     * @var string
     */
    private $state;

    /**
     * @param float $temperature
     * @param float $minTemperature
     * @param float $maxTemperature
     * @param string $state
     */
    public function __construct($temperature, $minTemperature, $maxTemperature, $state)
    {
        $this->temperature = $temperature;
        $this->minTemperature = $minTemperature;
        $this->maxTemperature = $maxTemperature;
        $this->state = $state;
    }

    /**
     * @return float
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * @return float
     */
    public function getMinTemperature()
    {
        return $this->minTemperature;
    }

    /**
     * @return float
     */
    public function getMaxTemperature()
    {
        return $this->maxTemperature;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}