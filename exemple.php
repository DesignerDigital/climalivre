<?php

use Climalivre\Clima;

require 'vendor/autoload.php';

$clima = new Clima();

echo "<pre>";
// API TEST
echo "#Api Test".PHP_EOL;
$response = $clima->test();
var_dump(json_decode($response));
echo "<hr>";

// get clima for IP Address
echo "#get clima for connection IP Address " . PHP_EOL;
$response = $clima->getForecastHere();
var_dump(json_decode($response));

//get clima for IP parameter required IP
echo "#get clima for parameter required IP" . PHP_EOL;
$response = $clima->getForecastHere('177.152.151.210');
var_dump(json_decode($response));
echo "<hr>";

//get clima for city or latitude and longitude
echo "#get clima for city or latitude and longitude without parametros" . PHP_EOL;
$response = $clima->getNow();
var_dump(json_decode($response));

//get clima for city or latitude and longitude
echo "#get clima for city or latitude and longitude with parameter city" . PHP_EOL;
$response = $clima->getNow(city:'Birigui');
var_dump(json_decode($response));

//get clima for city or latitude and longitude
echo "#get clima for city or latitude and longitude with parameter lat and lng" . PHP_EOL;
$response = $clima->getNow(lat: -20.0, lng:15.5);
var_dump(json_decode($response));
echo "<hr>";

//get week clima for city
echo "#get week clima for city" . PHP_EOL;
$response = $clima->getWeek('birigui');
// var_dump(json_decode($response));
echo "<hr>";

// get yesterday clima for city
echo "#get yesterday clima for city" . PHP_EOL;
$response = $clima->getYesterday('birigui');
var_dump(json_decode($response));
echo "<hr>";

// get temperature conversion
echo "#get temperature conversion" . PHP_EOL;
echo "Observação: valores permitidos para o parametro option: 'celsius_fahrenheit', 'celsius_kelvin', 'fahrenheit_celsius', 'fahrenheit_kelvin', 'kelvin_celsius', 'kelvin_fahrenheit'".PHP_EOL;
$response = $clima->temperatureConversion(75.5, 'celsius_kelvin');
var_dump(json_decode($response));
echo "<hr>";

// get sun Information for city
echo "#get sun Information for city" . PHP_EOL;
$response = $clima->getSunInformation('Birigui');
var_dump(json_decode($response));
echo "<hr>";

// get rain Information for city
echo "#get rain Information for city" . PHP_EOL;
$response = $clima->rainForecast('Birigui');
var_dump(json_decode($response));
echo "<hr>";

// get temperature comparation for city
echo "#get temperature comparation for city" . PHP_EOL;
$response = $clima->compareTemperature('Birigui');
var_dump(json_decode($response));
echo "</pre>";