# Open Weather Test

A small test app that makes use of the [OpenWeatherMap API](http://openweathermap.org/api). It allows for input of
a city and country code and will provide back (in metric units):

 - Current weather for the location entered
 - Daily forecast, for exactly 3 days, for the location entered including the temperature, min and max temperatures and
   the weather state (eg Rain, Snow, Extreme etc)


## Development Dependencies
 - `PHP 5.4+`


## Setting up a Development Environment
 1. `php composer.phar install`


## Running a Development Version
 1. Perform the steps outlined in Setting up a Development Environment
 2. `php -S localhost:8765 -t web/`
 3. You can now access the site from (http://localhost:8765)


## Running the tests
 NOTE: Test have only been run on *nix based machines. The integration tests will not run on a Windows system
 1. Perform the steps outlined in Setting up a Development Environment
 2. `vendor/bin/phpunit`