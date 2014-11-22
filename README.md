# Open Weather Test

A small test app that makes use of the [OpenWeatherMap API](http://openweathermap.org/api). It allows for input of
a city and country code and will provide back (in metric units):

 - Current weather for the location entered
 - Daily forecast, for exactly 3 days, for the location entered including the temperature, min and max temperatures and
   the weather state (eg Rain, Snow, Extreme etc)


## Setting up a Development Environment
 1. Ensure you have PHP CLI version 5.4+ installed
 2. `php composer.phar install`


## Running a Development Version
 1. Perform the steps outlined in Setting up a Development Environment
 2. `php -S 127.0.0.1:8765 -t web/`
 3. You can now access the site from [http://127.0.0.1:8765](http://127.0.0.1:8765)


## Running the tests
 NOTE: Tests have only been run on *nix based machines. The integration tests will not run on a Windows system
 1. Perform the steps outlined in Setting up a Development Environment
 2. `vendor/bin/phpunit`