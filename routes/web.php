<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response()->json(['status'=> 'ok','message' =>  'Bem vindo ao Clima Livre a sua api do tempo.', 'about' => 'credits from https://open-meteo.com/']);
});

$router->group(['prefix'=>'/climalivre'], function() use($router){
    $router->group(['prefix' => '/v1'], function() use($router){
        /** obtem a mensagem do clima atual na localização de onde foi solicitada a requisição */
        $router->get('/clima-local', 'ClimaV1Controller@getForecastHere');
        /** obtem o clima atual baseado em pesquisa por cidade ou coordenadas */
        $router->get('/hoje', 'ClimaV1Controller@getNow');
        /** obtem o clima da semana baseado em pesquisa por cidade  */
        $router->get('/semana', 'ClimaV1Controller@getWeek');
        /** obtem o clima de ontem baseado em pesquisa por cidade  */
        $router->get('/ontem', 'ClimaV1Controller@getYesterday');
        /** Rota para conversão de temperatura (C F K) */
        $router->get('/temperature-conversion', 'ClimaV1Controller@temperatureConversion');
        /** obtem informações sobre o nascer e o por do sol baseado em pesquisa por cidade  */
        $router->get('/sol[/{city}]', 'ClimaV1Controller@getSunInformation');
        /** obtem previsão de chuva nos proximos 3 dias baseado em pesquisa por cidade  */
        $router->get('/chuva[/{city}]', 'ClimaV1Controller@rainForecast');
        /** obtem uma comparação entre a temperatura de ontem e de hoje baseado em pesquisa por cidade  */
        $router->get('/comparar-temperatura[/{city}]', 'ClimaV1Controller@compareTemperature');
    });
});