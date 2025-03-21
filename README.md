# 🌦️ API de Clima - Lumen

Esta API fornece informações meteorológicas em tempo real utilizando a **Open-Meteo API** e a **OpenCage Geocoder** para geolocalização de cidades.

## 📌 Requisitos

- PHP 8+
- Composer
- Lumen 10+
- Extensões **cURL** e **JSON** habilitadas

## 🚀 Instalação

Clone o repositório e instale as dependências:

```sh
# Clonar o projeto
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio

# Instalar dependências
composer install

# Criar arquivo .env
cp .env.example .env
```

Edite o **.env** e adicione suas chaves de API:

```env
OPENCAGE_API_KEY=your-opencage-api-key
OPENMETEO_URL=https://api.open-meteo.com/v1/forecast
```

## 🔥 Como Usar

Inicie o servidor local:

```sh
php -S localhost:8000 -t public
```

Agora, a API pode ser acessada via **http://localhost:8000/climalivre/v1**

## 🌍 Endpoints Disponíveis

### 🔎 Buscar Clima Atual
```http
GET /climalivre/v1/hoje/{cidade}
```
#### 📥 Parâmetros:
| Parâmetro  | Tipo   | Obrigatório | Descrição |
|------------|--------|-------------|------------|
| `cidade`   | string | ✅ Sim | Nome da cidade a ser consultada |

#### 📤 Exemplo de Requisição:
```sh
curl -X GET "http://localhost:8000/climalivre/v1/hoje/birigui"
```

#### 📥 Exemplo de Resposta:
```json
{
  "cidade": "Birigui",
  "latitude": -21.28861,
  "longitude": -50.34,
  "temperatura": 27.5,
  "precipitacao": 0.2,
  "cobertura_nuvens": 35,
  "condicao_tempo": "Parcialmente nublado",
  "is_day": true
}
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