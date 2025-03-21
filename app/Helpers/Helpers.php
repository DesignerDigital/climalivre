<?php
if(!function_exists('cloud_cover')){
    function cloud_cover(string $value):string
    {
        if($value >= 0 && $value <= 30){
            return  "o céu está limpo ou com poucas nuvens";
        }elseif($value >30 && $value <= 70){
            return "o céu está parcialmente nublado";
        }else{
            return "o céu está nublado";
        }
    }
}

function getWeatherIcon(int $weatherCode):string
{
    $icons = [
        0 => '☀️',  // Céu limpo
        1 => '🌤️', // Poucas a muitas nuvens
        2 => '⛅',
        3 => '☁️',  
        45 => '🌫️', // Neblina
        48 => '🌫️', 
        51 => '🌦️', // Chuvisco
        53 => '🌦️',
        55 => '🌦️',  
        61 => '🌧️', // Chuva
        63 => '🌧️',
        65 => '🌧️',  
        80 => '🌦️', // Pancadas de chuva
        81 => '🌧️',
        82 => '🌧️',  
        95 => '⛈️', // Tempestades
        96 => '⛈️',
        99 => '⛈️',  
        71 => '❄️', // Neve
        73 => '❄️',
        75 => '❄️',  
        85 => '🌨️', // Pancadas de neve
        86 => '🌨️',  
    ];

    return $icons[$weatherCode] ?? '❓'; // Retorna um ícone padrão se não encontrar
}

function getWeatherDescription(int $weatherCode):string
{
    $descriptions = [
        0 => 'Céu limpo',
        1 => 'Poucas nuvens',
        2 => 'Parcialmente nublado',
        3 => 'Nublado',
        45 => 'Neblina',
        48 => 'Nevoeiro',
        51 => 'Chuvisco leve',
        53 => 'Chuvisco moderado',
        55 => 'Chuvisco intenso',
        61 => 'Chuva fraca',
        63 => 'Chuva moderada',
        65 => 'Chuva forte',
        80 => 'Pancadas de chuva fraca',
        81 => 'Pancadas de chuva moderada',
        82 => 'Pancadas de chuva forte',
        95 => 'Tempestade',
        96 => 'Tempestade com granizo',
        99 => 'Tempestade severa',
        71 => 'Neve fraca',
        73 => 'Neve moderada',
        75 => 'Neve forte',
        85 => 'Pancadas de neve fraca',
        86 => 'Pancadas de neve forte',
    ];
    return $descriptions[$weatherCode] ?? 'Condição desconhecida';
}


if (!function_exists('is_day')) {
    function is_day(bool $value):string
    {
        if($value){
            return 'o dia';
        }
        return 'a noite';   
    }
}

if(!function_exists('get_message')){
    function get_message($city, $data){
        return "Hoje " . cloud_cover($data->current->cloud_cover) . " em {$city}, com {$data->current->temperature_2m}{$data->current_units->temperature_2m} e umidade de {$data->current->relative_humidity_2m}{$data->current_units->relative_humidity_2m}. Aproveite " . is_day($data->current->is_day) . "!";
    }
}
if (!function_exists('get_week_en_to_br')) {
    function get_week_en_to_br(string $value):string
    {
        $weekDays = [
            'Sunday' => 'domingo',
            'Monday' => 'segunda-feira',
            'Tuesday' => 'terça-feira',
            'Wednesday' => 'quarta-feira',
            'Thursday' => 'quinta-feira',
            'Friday' => 'sexta-feira',
            'Saturday' => 'sábado'
        ];

        return $weekDays[$value];
      
    }
}

if(!function_exists('formatSecondsInHours')){
    function formatSecondsInHours($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        if ($hours > 0) {
            return "{$hours}h {$minutes}min";
        } else {
            return "{$minutes}min";
        }
    }
}

if(!function_exists('sunshinePercentage')){
    function sunshinePercentage($seconds, $totalDaylight = 43200)
    {
        return round(($seconds / $totalDaylight) * 100, 1) . "%";
    }
}

if(!function_exists('getPrecipitationProbability')){
    function getPrecipitationProbability($probability)
    {
        if ($probability >= 80) return "⛈️ Alta chance de chuva";
        if ($probability >= 50) return "🌧️ Provável chuva";
        if ($probability >= 20) return "🌥️ Pode chover";
        return "☀️ Sem chuva esperada";
    }
}

if(!function_exists('conversion')){
    function conversion($value, $option):array 
    {
        if($option == 'celsius_fahrenheit' || $option == 'celsius_kelvin'){
            if($option == 'celsius_fahrenheit'){
                $conversion = ($value * 9/5) + 32;
                $conversion .= '°F';
            }

            if ($option == 'celsius_kelvin') {
                $conversion = $value + 273.15;
                $conversion .= '°K';
            }
                $value .= '°C'; 
        }

        if ($option == 'fahrenheit_celsius' || $option == 'fahrenheit_kelvin') {
            if($option == 'fahrenheit_celsius'){
                $conversion = ($value -32) * 5/9;
                $conversion .= '°C';
            }

            if ($option == 'fahrenheit_kelvin') {
                $conversion = ($value - 32) * 5/9 + 273.15;
                $conversion .= '°K';
            }
            $value .= '°F';
        }
        if ($option == 'kelvin_celsius' || $option == 'kelvin_fahrenheit') {
            if ($option == 'kelvin_celsius') {
                $conversion = $value - 273.15;
                $conversion .= '°C';
            }

            if ($option == 'kelvin_fahrenheit') {
                // F = (K - 273.15) × 9/5 + 32
                $conversion = ($value - 273.15) * 9/5 + 32;
                $conversion .= '°F';
            }
            $value .= '°K';
        }
        return [
            'option' => implode(' para ', explode('_', $option)),
            'temperature' => $value,
            'conversion' => $conversion
        ];
    }
}

if (!function_exists('can_it_rain')) {
    function can_it_rain(float $value){
            // 0.0 mm	Sem chuva ☀️
            // 0.1 - 1.0 mm	Garoa leve 🌦️
            // 1.0 - 5.0 mm	Chuva fraca ☔
            // 5.0 - 20.0 mm	Chuva moderada 🌧️
            // 20.0+ mm	Chuva forte ou tempestade ⛈️
            $msg = '';
        if ($value >= 20.0) {
           $msg .= "há risco de chuva forte ou tempestade";
        } elseif ($value >= 5.0 && $value < 20.0) {
            $msg .= "Pode haver chuva moderada";
        } elseif ($value >= 1.0 && $value < 5.0) {
            $msg .= "Pode haver chuva fraca";
        } elseif ($value >= 0.1 && $value < 1.0) {
            $msg .= "Pode haver garoa leve ";
        } else {
            return "Sem previsão de chuva. para os próximos 3 dias";
        }

        $msg .= " nos próximos 3 dias! (Total: {$value} mm)";
        return $msg;
    }
}

if(!function_exists('compareTemperature')){
    function compareTemperature(array $temperatures, string $units):object|null
    {
     
        if(!is_array($temperatures) || count($temperatures) != 2){
            return null;
        }
        if($temperatures[0] == $temperatures[1]){
            $msg =  'Não houve diferença na temperatura entre ontem e hoje';
        }elseif ($temperatures[0] > $temperatures[1]) {
            $msg = 'Ontem o dia foi mais quente que hoje';
        }else{
            $msg = 'Ontem o dia foi mais frio que hoje';
        }

        return (object)[
            'yesterday' => $temperatures[0].$units,
            'today' => $temperatures[1] . $units,
            'message' => $msg??null
        ];
    }
}