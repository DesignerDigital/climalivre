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
```

### 🌎 Buscar Clima para Qualquer Localização
```http
GET /climalivre/v1/hoje?city={cidade}&state={estado}&country={pais}
```

#### 📤 Exemplo:
```sh
curl -X GET "http://localhost:8000/climalivre/v1/hoje?city=São%20Paulo&state=SP&country=BR"
```

---

## ⚡ Erros Comuns e Soluções

| Código HTTP | Mensagem | Causa |
|-------------|------------|----------------|
| 400 | `Parâmetro cidade ausente` | A cidade não foi informada na URL |
| 404 | `Cidade não encontrada` | O nome pode estar incorreto ou não há dados para a cidade |
| 500 | `Erro Interno` | Verifique logs e chaves de API |

---

## 📜 Licença
Este projeto é distribuído sob a licença MIT.