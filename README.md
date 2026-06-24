# OLX Price Tracker

A Laravel application for monitoring OLX advertisement prices and notifying subscribers when prices change.

---

# Features

- Track OLX advertisement prices
- Email subscriptions
- Email verification
- Scheduled price checks
- Price history storage
- Dashboard with chart and history table
- Queue-based notifications
- REST API
- Dockerized environment
- PostgreSQL + Redis

---

# Architecture

```text
HTTP Request
      |
Controllers
      |
Form Requests
      |
Services
      |
Jobs / Queue
      |
Models
      |
PostgreSQL
```

Main layers:

- Controllers – web and API endpoints
- Form Requests – validation
- Services – business logic
- Jobs – background processing
- Mail – notifications
- Models – persistence

---

# Database Schema

## advertisements

| Column | Type |
|----------|----------|
| id | bigint |
| olx_id | string |
| title | string |
| url | text |
| last_price_value | integer |
| last_currency | string |
| last_checked_at | timestamp |
| created_at | timestamp |
| updated_at | timestamp |

---

## subscriptions

| Column | Type |
|----------|----------|
| id | bigint |
| advertisement_id | bigint |
| email | string |
| verification_token | string |
| verification_token_expires_at | timestamp |
| verified_at | timestamp |
| created_at | timestamp |
| updated_at | timestamp |

---

## price_histories

| Column | Type |
|----------|----------|
| id | bigint |
| advertisement_id | bigint |
| price_value | integer |
| currency | string |
| created_at | timestamp |
| updated_at | timestamp |

---

# Services

## OlxPriceFetcher

Responsible for:

- Parsing OLX page
- Extracting:
    - title
    - price
    - currency
    - olx id
- Safe fallback strategy

Fallback levels:

1. Structured JSON data
2. DOM parsing
3. Regex extraction

---

## DashboardService

Responsible for:

- Advertisement list
- Price history
- Chart datasets

---

## NbuExchangeService

Responsible for:

- Fetching NBU exchange rates
- Currency conversion
- Exchange rate lookup

---

# Queue Jobs

## DispatchCheckPriceJob

Finds advertisements requiring update and dispatches checking jobs.

---

## CheckPriceJob

Responsible for:

- Fetching current advertisement state
- Detecting price changes
- Saving history
- Updating advertisement snapshot
- Dispatching notifications

---

## NotifySubscribersJob

Responsible for:

- Sending price change notifications
- Processing verified subscriptions only

---

# Scheduler

Scheduler runs periodically and dispatches:

```text
DispatchCheckPriceJob
        ↓
CheckPriceJob
        ↓
NotifySubscribersJob
```

Run scheduler:

```bash
php artisan schedule:work
```

---

# API

## Create subscription

```http
POST /api/subscriptions
```

Request:

```json
{
  "url": "https://www.olx.ua/...",
  "email": "user@example.com"
}
```

Response:

```json
{
  "success": true
}
```

---

# Web Routes

| Method | Route |
|----------|----------|
| GET | / |
| POST | /subscriptions |
| GET | /dashboard |
| GET | /dashboard/advertisements/{id} |
| GET | /verify-email/{token} |

---

# Docker Services

## app

Laravel application container.

## nginx

Web server.

## postgres

Main database.

## redis

Queue backend.

## queue

Queue worker.

## scheduler

Laravel scheduler.

---

# Installation

## Clone repository

```bash
git clone <repository-url>
cd olx-price-tracker
```

---

## Configure environment

```bash
cp .env.example .env
```

## Start containers

```bash
docker compose up -d --build
```

## Install Composer
```bash
docker compose exec app composer install
```

---


## Generate application key

```bash
docker compose exec app php artisan key:generate
```

---

## Run migrations

```bash
docker compose exec app php artisan migrate
```

---

# Running Workers

Queue:

```bash
docker compose exec app php artisan queue:work
```

Scheduler:

```bash
docker compose exec app php artisan schedule:work
```

---
# RUN APPLICATION(WEB VERSION)
 ```bash
 http://localhost:8080/
 ```
## email client
```bash
http://localhost:8025/
```
## dashboard
```bash
http://localhost:8080/dashboard
```

---
# Testing

Run tests:

```bash
docker compose exec app php artisan test
```





---



# Production Notes

For production deployment:

- Enable HTTPS
- Configure Supervisor for workers
- Configure scheduled tasks
- Use Redis queues
- Configure SMTP provider
- Enable monitoring and logging

---

# License

MIT
