<?php 

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ClimaV1Controller extends Controller{
    protected $client;
    private $patternCity = 'Brasília';

    public function __construct(GuzzleConntroller $client)
    {
        $this->client = $client;
    }

    public function getForecastHere(Request $request)
    {
        $response =  $this->client->requisition('http://ip-api.com/json/'.$request->ip(), [], false);

        $ip = $response->getBody();


        if(empty($ip->lat) || empty($ip->lon)){
            return response()->json(['message' => 'não foi possivel encontrar a sua localização'],400);
        }
        try {
            $response = $this->client->requisition($this->urlSearchTime, [
                'latitude' => $ip->lat,
                'longitude' => $ip->lon,
                'current' => 'temperature_2m,relative_humidity_2m,rain,is_day,weather_code,cloud_cover,showers,snowfall',
                'timezone' => 'America/Sao_Paulo',
                'forecast_days' => 1
            ], false);

            $data = $response->getBody();
            return response()->json(['message' => get_message(urldecode($ip->city), $data), 'about' => 'credits from https://open-meteo.com/']);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()]);
        }
    }
    
    public function getNow(Request $request){
        $city = !empty($request->query('city')) ? urldecode($request->query('city')) : null;
        $lat = !empty($request->query('lat')) ? $request->query('lat'): null;
        $lng = !empty($request->query('lng')) ? $request->query('lng') : null;

        if(!empty($city)){
            $result = $this->getCoordinates($city);
        }elseif(!empty($lat) && !empty($lng)){
            $result = (object)[
                'latitude' => $lat,
                'longitude' => $lng
            ]; 
        }else{
            $result = $this->getCoordinates(urldecode($this->getPatternCity()));
        }
        if(empty($result) || empty($result->latitude) || empty($result->longitude)){
            return response()->json([
                'message' => 'Valores de latitude e longitude incorretos ou não enviados.'
            ]);
        }

        try{
            $response = $this->client->requisition($this->urlSearchTime, [
                'latitude' => $result->latitude,
                'longitude' => $result->longitude,
                'current' => 'temperature_2m,relative_humidity_2m,rain,is_day,weather_code,cloud_cover',
                'timezone' => 'America/Sao_Paulo',
                'forecast_days' => 1
            ], false);

            $data = $response->getBody();

            return response()->json(['locate' => $result->locate ??'Busca por coordenadas Lat: '.$data->latitude.' e Lng: '.$data->longitude, 'clima' => [
                'temperature_2m'  => $data->current->temperature_2m . $data->current_units->temperature_2m,
                'relative_humidity_2m' => $data->current->relative_humidity_2m . $data->current_units->relative_humidity_2m,
                'is_day' => $data->current->is_day == true,
                'description' => getWeatherDescription($data->current->weather_code),
                'weather_code' => $data->current->weather_code,
                'icon'=> getWeatherIcon($data->current->weather_code),
                'cloud_cover' => cloud_cover($data->current->cloud_cover)
            ], 'about' => 'credits from https://open-meteo.com/']);
        }catch(\Exception $err){
            return response()->json(['message' => $err->getMessage()]);
        }
    }

    public function getWeek(Request $request)
    {
        $city = !empty($request->query('city')) ? urldecode($request->query('city')) : 'Brasília';

        $result = $this->getCoordinates($city);
        if (empty($result) || empty($result->latitude) || empty($result->longitude)) {
            return response()->json([
                'message' => 'Valores de latitude e longitude incorretos ou não enviados ou a cidade não pode ser localizada.'
            ]);
        }

        try {
            $response = $this->client->requisition($this->urlSearchTime, [
                'latitude' => $result->latitude,
                'longitude' => $result->longitude,
                'daily' => 'weather_code,apparent_temperature_min,temperature_2m_max,sunshine_duration,rain_sum,precipitation_probability_max,shortwave_radiation_sum,sunrise,uv_index_max,showers_sum,wind_speed_10m_max,et0_fao_evapotranspiration,temperature_2m_min,sunset,uv_index_clear_sky_max,snowfall_sum,wind_gusts_10m_max,apparent_temperature_max,daylight_duration,precipitation_sum,precipitation_hours,wind_direction_10m_dominant',
                'timezone' => 'America/Sao_Paulo'
            ], false);

            $data = $response->getBody();
            $results = [];
            $daily = $data->daily; 
            $dailyUnits = $data->daily_units;
            if(is_object($daily) && count(get_object_vars($daily)) > 0){
                $today = Carbon::today();
                for ($i = 0; $i < 7; $i++) {
                    $date = Carbon::parse($daily->time[$i]);
                    $dateSunrise = Carbon::parse($daily->sunrise[$i]);
                    $dateSunset = Carbon::parse($daily->sunset[$i]);
                    $results[] = (object)[
                        'dia' => get_week_en_to_br($date->translatedFormat('l')),
                        'temperatura_2m_min' => $daily->temperature_2m_min[$i] . $dailyUnits->temperature_2m_min,
                        'temperatura_2m_max' => $daily->temperature_2m_max[$i] . $dailyUnits->temperature_2m_max,
                        'temperatura_aparente_min' => $daily->apparent_temperature_min[$i] . $dailyUnits->apparent_temperature_min,
                        'temperatura_aparente_max' => $daily->apparent_temperature_max[$i] . $dailyUnits->apparent_temperature_max,
                        'descricao_clima' => getWeatherDescription($daily->weather_code[$i]),
                        'icone_clima' => getWeatherIcon($daily->weather_code[$i]),
                        'duracao_sol' => formatSecondsInHours($daily->sunshine_duration[$i]),
                        'duracao_luz_dia' =>   formatSecondsInHours($daily->daylight_duration[$i]),
                        'porcentagem_duracao_sol' => sunshinePercentage($daily->sunshine_duration[$i]),
                        'soma_precipitacao' =>  $daily->precipitation_sum[$i] . $dailyUnits->precipitation_sum,
                        'probabilidade_precipitacao_valor_max' => $daily->precipitation_probability_max[$i] . $dailyUnits->precipitation_probability_max,
                        'precipitation_probability_max' => getPrecipitationProbability($daily->precipitation_probability_max[$i]),
                        'precipitacao_horas' => $daily->precipitation_hours[$i] . $dailyUnits->precipitation_hours,
                        'soma_chuva_continua' => $daily->rain_sum[$i] . ' ' . $dailyUnits->rain_sum,
                        'soma_radiação_onda_curta' => $daily->shortwave_radiation_sum[$i] .  $dailyUnits->shortwave_radiation_sum,
                        'nascer_do_sol' => $dateSunrise->format('H:i'),
                        'por_do_sol' =>  $dateSunset->format('H:i'),
                        'indice_uv_max' => $daily->uv_index_max[$i],
                        'pancadas_chuva' => $daily->showers_sum[$i] . $dailyUnits->showers_sum,
                        'velocidade_vento_10m_max' => $daily->wind_speed_10m_max[$i] . $dailyUnits->wind_speed_10m_max,
                        'direcao_vento_10m_dominante' => $daily->wind_direction_10m_dominant[$i] . $dailyUnits->wind_direction_10m_dominant,
                        'evotranspiracao' => $daily->et0_fao_evapotranspiration[$i] . $dailyUnits->et0_fao_evapotranspiration,
                        'soma_neve' => $daily->snowfall_sum[$i] . $dailyUnits->snowfall_sum,
                    ];
                    $today->addDay();
                }
            }
           
            
            return response()->json(['locate' => $result->locate, 'results' => $results,'about' => 'credits from https://open-meteo.com/']);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()]);
        }
    }

    public function getYesterday(Request $request)
    {
        $city = !empty($request->query('city')) ? urldecode($request->query('city')) : 'Brasília';

        $result = $this->getCoordinates($city);
        if (empty($result) || empty($result->latitude) || empty($result->longitude)) {
            return response()->json([
                'message' => 'Valores de latitude e longitude incorretos ou não enviados ou a cidade não pode ser localizada.'
            ]);
        }

        try{
            $response = $this->client->requisition($this->urlSearchTime, [
                'latitude' => $result->latitude,
                'longitude' => $result->longitude,
                'daily' => 'temperature_2m_max,temperature_2m_min',
                'timezone' => 'America/Sao_Paulo',
                'past_days' =>1 ,
                'forecast_days'=>1
            ], false);

            $data = $response->getBody();

            $yesterday = $data->daily->time[0];
            $max = $data->daily->temperature_2m_max[0];
            $min = $data->daily->temperature_2m_min[0];

            $averageTemperature = ($min + $max)/2;

            return response()->json(['locate' => $result->locate,'date'=>$yesterday, 'temperatura_media' =>  $averageTemperature. $data->daily_units->temperature_2m_max, 'about' => 'credits from https://open-meteo.com/']);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()]);
        }
    }

    public function temperatureConversion(Request $request)
    {
        $temperature = (float) $request->query('temperature');
        $option = !empty($request->query('option'))? $request->query('option'): 'celsius_fahrenheit';
        if(empty($temperature)){
            return response()->json(['message' =>'A temperatura é um parametro obrigatório'],400);
        }
        $result = conversion($temperature, $option);
       
        return response()->json(['result' => $result, 'about' => 'credits from https://open-meteo.com/']);
    }

    public function getSunInformation(?string $city = null)
    {
        $city = !empty($city) ? urldecode($city) : 'Brasília';

        $result = $this->getCoordinates($city);
        if (empty($result) || empty($result->latitude) || empty($result->longitude)) {
            return response()->json([
                'message' => 'Valores de latitude e longitude incorretos ou não enviados ou a cidade não pode ser localizada.'
            ]);
        }

        try {
            $response = $this->client->requisition($this->urlSearchTime, [
                'latitude' => $result->latitude,
                'longitude' => $result->longitude,
                'daily' => 'sunrise,sunset',
                'timezone' => 'America/Sao_Paulo',
                'forecast_days' => 1
            ], false);

            $data = $response->getBody();
       
            $daily = $data->daily;
            $dailyUnits = $data->daily_units;
            if (is_object($daily) && count(get_object_vars($daily)) > 0) {
                for ($i = 0; $i < 1; $i++) {
                    $dateSunrise = Carbon::parse($daily->sunrise[$i]);
                    $dateSunset = Carbon::parse($daily->sunset[$i]);
                
                    $results[] = (object)[
                        'nascer_do_sol' => $dateSunrise->format('H:i'),
                        'por_do_sol' =>  $dateSunset->format('H:i'),
                    ];
                }
            }


            return response()->json(['locate' => $result->locate, 'results' => $results, 'about' => 'credits from https://open-meteo.com/']);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()]);
        }
    }

    public function rainForecast(?string $city = null)
    {
        $city = !empty($city) ? urldecode($city) : 'Brasília';

        $result = $this->getCoordinates($city);
        if (empty($result) || empty($result->latitude) || empty($result->longitude)) {
            return response()->json([
                'message' => 'Valores de latitude e longitude incorretos ou não enviados ou a cidade não pode ser localizada.'
            ]);
        }
        try {
            $response = $this->client->requisition($this->urlSearchTime, [
                'latitude' => $result->latitude,
                'longitude' => $result->longitude,
                'daily' => 'precipitation_sum',
                'timezone' => 'America/Sao_Paulo',
                'forecast_days' => 3
            ], false);

            $data = $response->getBody();

            $daily = $data->daily;
            $dailyUnits = $data->daily_units;

            $rainSum = 0;
            if (is_object($daily) && count(get_object_vars($daily)) > 0) {
                for ($i = 0; $i < 3; $i++) {
                    $rainSum += $daily->precipitation_sum[$i];
                }
            }

            $canRain = can_it_rain($rainSum);
                // dd($rain);

            return response()->json(['locate' => $result->locate, 'can_rain' => $canRain, 'about' => 'credits from https://open-meteo.com/']);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()]);
        }
    }

    public function compareTemperature(?string $city = null)
    {
        $city = !empty($city) ? urldecode($city) : 'Brasília';
        $result = $this->getCoordinates($city);
        if (empty($result) || empty($result->latitude) || empty($result->longitude)) {
            return response()->json([
                'message' => 'Valores de latitude e longitude incorretos ou não enviados ou a cidade não pode ser localizada.'
            ]);
        }

        try {
            $response = $this->client->requisition($this->urlSearchTime, [
                'latitude' => $result->latitude,
                'longitude' => $result->longitude,
                'daily' => 'temperature_2m_max,temperature_2m_min',
                'timezone' => 'America/Sao_Paulo',
                'past_days' => 1,
                'forecast_days' => 1
            ], false);

            $data = $response->getBody();
            $averageTemperature = [];
          
            for($i = 0; $i< 2;$i++){
           
                $max = $data->daily->temperature_2m_max[$i];
                $min = $data->daily->temperature_2m_min[$i];

                $averageTemperature[] = ($min + $max) / 2;
            }
            
            $compare = compareTemperature($averageTemperature, $data->daily_units->temperature_2m_max);
            if(!$compare){
                return response()->json(['message' => 'Dados inválidos para fazer a comparação']);
            }
            return response()->json(['locate' => $result->locate, 'compare' => $compare->message, 'temperature_yesterday' => $compare->yesterday,
                'temperature_today' => $compare->today, 'about' => 'credits from https://open-meteo.com/']);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()]);
        }
    }

    private function getCoordinates(string $city): object
    {
        try {
            $responseLocate = $this->client->requisition($this->urlSearch, [
                'name' => $city,
                'language' => 'pt-br',
                'format' => 'json',
                'count' => 1
            ], false);

            $dataLocate = $responseLocate->getBody();

            if (empty($dataLocate->results) || empty($dataLocate->results[0])) {
                return response()->json(['message' => 'Cidade Não encontrada!']);
            }

            $dataLocate = $dataLocate->results[0];
            $result = (object)[
                'locate' => $dataLocate->name . ' ' . $dataLocate->admin1 . ' - ' . $dataLocate->country_code,
                'latitude' => $dataLocate->latitude,
                'longitude' => $dataLocate->longitude
            ];

            return $result;
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()]);
        }
    }

    public function getPatternCity()
    {
        return $this->patternCity;

    }
}