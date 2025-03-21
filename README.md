# 🌦️ API de Clima - Lumen

Esta API fornece informações meteorológicas em tempo real utilizando a **Open-Meteo API** para geolocalização de cidades.

## 📌 Requisitos

- PHP 8+
- Composer
- Lumen 10+
- Extensões **cURL** e **JSON** habilitadas

## 🚀 Instalação

Clone o repositório e instale as dependências:

```sh
# Clonar o projeto
git clone https://github.com/DesignerDigital/climalivre.git
cd climalivre

# Instalar dependências
composer install

## 🔥 Como Usar

Inicie o servidor local:

```sh
php -S localhost:8000 -t public
```

Agora, a API pode ser acessada via **http://localhost:8000/climalivre/v1**

## 🌍 Endpoints Disponíveis

###  Testar a API
```http
GET /
```

#### 📤 Exemplo de Requisição:
```sh
curl -X GET "http://localhost:8000"
```

#### 📥 Exemplo de Resposta:
```json
{
	"status": "ok",
	"message": "Bem vindo ao Clima Livre a sua api do tempo.",
	"about": "credits from https://open-meteo.com/"
}
```

###  Buscar o clima local busca por IP
Observação: Este endpoint não retorna resultados se executado em servidor local.

porém você pode alterar o ClimaV1Controller.php:19  $response =  $this->client->requisition('http://ip-api.com/json/'.$request->ip(), [], false);
substituindo $request->ip() pelo seu ip http://ip-api.com/json/{seu-ip}'

```http
GET /clima-local
```

#### 📤 Exemplo de Requisição:
```sh
curl -X GET "http://localhost:8000/clima-local"
```

#### 📥 Exemplo de Resposta:
```json
{
	"message": "Hoje o céu está limpo ou com poucas nuvens em Birigui, com 23°C e umidade de 60%. Aproveite a noite!",
	"about": "credits from https://open-meteo.com/"
}
```

### 🔎 Buscar Clima Atual
```http
GET /climalivre/v1/hoje?city=birigui
```
```http
GET /climalivre/v1/hoje?lat=-20.0&lng=15.5
```
Observações: Enviando  a cidade, lat e lng, será priorizado a busca pela cidade, é necessário enviar valor da lat e lng para que a busca por coordenadas seja retornada com exito, caso falte um dos dois parametros a busca sera confirmada pela cidade padrão ou seja 'Brasilia'

#### 📥 Parâmetros:
| Parâmetro | Tipo   | Obrigatório | Default   | Descrição |<br>
| `city`    | string | ❌ Não      | Brasilia  | Nome da cidade a ser consultada |<br>
| `lat`     | string | ❌ Não      | null  | Latitude a ser consultada |<br>
| `lng`     | string | ❌ Não      | null  | Longitude a ser consultada |<br>

#### 📤 Exemplo de Requisição:
```sh
curl -X GET "http://localhost:8000/climalivre/v1/hoje?city=birigui"

curl -X GET "http://localhost:8000/climalivre/v1/hoje?lat=-20.0&lng=15.5"
```

#### 📥 Exemplo de Resposta:
```json
{
	"locate": "Birigui São Paulo - BR",
	"clima": {
		"temperature_2m": "22.8°C",
		"relative_humidity_2m": "60%",
		"is_day": false,
		"description": "Céu limpo",
		"weather_code": 0,
		"icon": "☀️",
		"cloud_cover": "o céu está limpo ou com poucas nuvens"
	},
	"about": "credits from https://open-meteo.com/"
}
```
editando ------

### 🔎 Previsão do tempo para os próximos 7 dias
```http
GET /climalivre/v1/semana?city=birigui
```

#### 📥 Parâmetros:
| Parâmetro | Tipo   | Obrigatório | Default   | Descrição |<br>
| `city`    | string | ❌ Não      | Brasilia  | Nome da cidade a ser consultada |<br>


#### 📤 Exemplo de Requisição:
```sh
curl -X GET "http://localhost:8000/climalivre/v1/semana?city=birigui"
```

#### 📥 Exemplo de Resposta:
```json
{
	"locate": "Birigui São Paulo - BR",
	"results": [
		{
			"dia": "sexta-feira",
			"temperatura_2m_min": "18.9°C",
			"temperatura_2m_max": "32.2°C",
			"temperatura_aparente_min": "19°C",
			"temperatura_aparente_max": "35.7°C",
			"descricao_clima": "Parcialmente nublado",
			"icone_clima": "⛅",
			"duracao_sol": "11h 8min",
			"duracao_luz_dia": "12h 5min",
			"porcentagem_duracao_sol": "92.9%",
			"soma_precipitacao": "0mm",
			"probabilidade_precipitacao_valor_max": "5%",
			"precipitation_probability_max": "☀️ Sem chuva esperada",
			"precipitacao_horas": "0h",
			"soma_chuva_continua": "0 mm",
			"soma_radiação_onda_curta": "25.28MJ/m²",
			"nascer_do_sol": "06:25",
			"por_do_sol": "18:31",
			"indice_uv_max": 8.4,
			"pancadas_chuva": "0mm",
			"velocidade_vento_10m_max": "17.4km/h",
			"direcao_vento_10m_dominante": "124°",
			"evotranspiracao": "5.61mm",
			"soma_neve": "0cm"
		},
		{
			"dia": "sábado",
			"temperatura_2m_min": "20.9°C",
			"temperatura_2m_max": "32.7°C",
			"temperatura_aparente_min": "22.5°C",
			"temperatura_aparente_max": "37.1°C",
			"descricao_clima": "Nublado",
			"icone_clima": "☁️",
			"duracao_sol": "11h 5min",
			"duracao_luz_dia": "12h 4min",
			"porcentagem_duracao_sol": "92.4%",
			"soma_precipitacao": "0.1mm",
			"probabilidade_precipitacao_valor_max": "35%",
			"precipitation_probability_max": "🌥️ Pode chover",
			"precipitacao_horas": "1h",
			"soma_chuva_continua": "0 mm",
			"soma_radiação_onda_curta": "21.93MJ/m²",
			"nascer_do_sol": "06:26",
			"por_do_sol": "18:30",
			"indice_uv_max": 8.35,
			"pancadas_chuva": "0.1mm",
			"velocidade_vento_10m_max": "12.5km/h",
			"direcao_vento_10m_dominante": "73°",
			"evotranspiracao": "5mm",
			"soma_neve": "0cm"
		},
		{
			"dia": "domingo",
			"temperatura_2m_min": "22.3°C",
			"temperatura_2m_max": "30°C",
			"temperatura_aparente_min": "26.1°C",
			"temperatura_aparente_max": "34.7°C",
			"descricao_clima": "Tempestade",
			"icone_clima": "⛈️",
			"duracao_sol": "6h 0min",
			"duracao_luz_dia": "12h 3min",
			"porcentagem_duracao_sol": "50%",
			"soma_precipitacao": "12.8mm",
			"probabilidade_precipitacao_valor_max": "70%",
			"precipitation_probability_max": "🌧️ Provável chuva",
			"precipitacao_horas": "13h",
			"soma_chuva_continua": "0 mm",
			"soma_radiação_onda_curta": "16.02MJ/m²",
			"nascer_do_sol": "06:26",
			"por_do_sol": "18:29",
			"indice_uv_max": 8.3,
			"pancadas_chuva": "12.8mm",
			"velocidade_vento_10m_max": "11.3km/h",
			"direcao_vento_10m_dominante": "46°",
			"evotranspiracao": "3.34mm",
			"soma_neve": "0cm"
		},
		{
			"dia": "segunda-feira",
			"temperatura_2m_min": "22.4°C",
			"temperatura_2m_max": "29.3°C",
			"temperatura_aparente_min": "25.8°C",
			"temperatura_aparente_max": "33.9°C",
			"descricao_clima": "Tempestade com granizo",
			"icone_clima": "⛈️",
			"duracao_sol": "10h 46min",
			"duracao_luz_dia": "12h 1min",
			"porcentagem_duracao_sol": "89.8%",
			"soma_precipitacao": "11.3mm",
			"probabilidade_precipitacao_valor_max": "83%",
			"precipitation_probability_max": "⛈️ Alta chance de chuva",
			"precipitacao_horas": "21h",
			"soma_chuva_continua": "0 mm",
			"soma_radiação_onda_curta": "15.94MJ/m²",
			"nascer_do_sol": "06:26",
			"por_do_sol": "18:28",
			"indice_uv_max": 8.25,
			"pancadas_chuva": "11.3mm",
			"velocidade_vento_10m_max": "12.7km/h",
			"direcao_vento_10m_dominante": "38°",
			"evotranspiracao": "3.27mm",
			"soma_neve": "0cm"
		},
		{
			"dia": "terça-feira",
			"temperatura_2m_min": "21.4°C",
			"temperatura_2m_max": "30.4°C",
			"temperatura_aparente_min": "25.3°C",
			"temperatura_aparente_max": "34.7°C",
			"descricao_clima": "Nublado",
			"icone_clima": "☁️",
			"duracao_sol": "10h 45min",
			"duracao_luz_dia": "12h 0min",
			"porcentagem_duracao_sol": "89.7%",
			"soma_precipitacao": "2.3mm",
			"probabilidade_precipitacao_valor_max": "73%",
			"precipitation_probability_max": "🌧️ Provável chuva",
			"precipitacao_horas": "10h",
			"soma_chuva_continua": "0 mm",
			"soma_radiação_onda_curta": "17.4MJ/m²",
			"nascer_do_sol": "06:26",
			"por_do_sol": "18:27",
			"indice_uv_max": 6.45,
			"pancadas_chuva": "2.3mm",
			"velocidade_vento_10m_max": "12.1km/h",
			"direcao_vento_10m_dominante": "337°",
			"evotranspiracao": "3.63mm",
			"soma_neve": "0cm"
		},
		{
			"dia": "quarta-feira",
			"temperatura_2m_min": "22.5°C",
			"temperatura_2m_max": "29.8°C",
			"temperatura_aparente_min": "26.9°C",
			"temperatura_aparente_max": "34.5°C",
			"descricao_clima": "Tempestade",
			"icone_clima": "⛈️",
			"duracao_sol": "8h 47min",
			"duracao_luz_dia": "11h 59min",
			"porcentagem_duracao_sol": "73.3%",
			"soma_precipitacao": "11.7mm",
			"probabilidade_precipitacao_valor_max": "67%",
			"precipitation_probability_max": "🌧️ Provável chuva",
			"precipitacao_horas": "23h",
			"soma_chuva_continua": "0 mm",
			"soma_radiação_onda_curta": "17.38MJ/m²",
			"nascer_do_sol": "06:27",
			"por_do_sol": "18:26",
			"indice_uv_max": 7.65,
			"pancadas_chuva": "12mm",
			"velocidade_vento_10m_max": "10.5km/h",
			"direcao_vento_10m_dominante": "261°",
			"evotranspiracao": "3.59mm",
			"soma_neve": "0cm"
		},
		{
			"dia": "quinta-feira",
			"temperatura_2m_min": "21.9°C",
			"temperatura_2m_max": "31.1°C",
			"temperatura_aparente_min": "22.1°C",
			"temperatura_aparente_max": "36.3°C",
			"descricao_clima": "Pancadas de chuva fraca",
			"icone_clima": "🌦️",
			"duracao_sol": "11h 13min",
			"duracao_luz_dia": "11h 58min",
			"porcentagem_duracao_sol": "93.6%",
			"soma_precipitacao": "0.9mm",
			"probabilidade_precipitacao_valor_max": "41%",
			"precipitation_probability_max": "🌥️ Pode chover",
			"precipitacao_horas": "4h",
			"soma_chuva_continua": "0 mm",
			"soma_radiação_onda_curta": "19.67MJ/m²",
			"nascer_do_sol": "06:27",
			"por_do_sol": "18:25",
			"indice_uv_max": 7.65,
			"pancadas_chuva": "0.9mm",
			"velocidade_vento_10m_max": "26km/h",
			"direcao_vento_10m_dominante": "146°",
			"evotranspiracao": "4.18mm",
			"soma_neve": "0cm"
		}
	],
	"about": "credits from https://open-meteo.com/"
}
```


## 📜 Licença
Este projeto é distribuído sob a licença MIT.