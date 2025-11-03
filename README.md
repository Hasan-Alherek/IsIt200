# IsIt200

A lightweight backend service for website and API monitoring, built with Symfony.

## Overview

- IsIt200 is a backend-only Symfony application for automated monitoring of websites and APIs.
It sends HTTP requests every minute to check the availability and status of registered URLs.

- The project uses Symfony Scheduler and Symfony Messenger to handle periodic tasks and asynchronous processing efficiently.
- Symfony EventSubscriber → for centralized exception handling (all errors and exceptions are captured and returned as JSON responses)
- Monitoring results are stored in the database and kept for 24 hours.

## Features

- Monitor any website or API endpoint

- Sends requests automatically every minute

- Periodic checks managed by Symfony Scheduler

- Asynchronous task handling with Symfony Messenger

- Stores response data for 24 hours

- Manage monitored sites via REST API (CRUD operations)
  
- All responses in JSON

## Tech Stack

- Framework: Symfony 6.4

- Language: PHP 8.1

- Task Scheduling: Symfony Scheduler

- Queue System: Symfony Messenger

- HTTP Client: Symfony HttpClient

- Database: MySQL / PostgreSQL (configurable)

## How It Works

1. You register websites or APIs you want to monitor.

2. The Symfony Scheduler triggers HTTP requests every minute.

3. Each request is processed asynchronously using Messenger.

4. The response (status, latency, etc.) is stored in the database.

5. Records are automatically deleted after 24 hours.

6. You can interact with the system entirely through JSON-based REST endpoints.

## API Endpoints
- ### GET /

  - Lists all monitored websites.

  - Each website includes its last known status (e.g. 200).

  - Returns JSON data.

- ### GET /website/{id}

  - Returns full details for a specific website.

  - Includes all status logs for the past 24 hours.

  - Response in JSON.

- ### POST /website/add

  - Adds a new website to monitor.

  - Body parameters:
  
    ```
    {
      "name": "Example Site",
      "url": "https://example.com"
    }
    ```
  - Returns a success message in JSON.

- ### PUT /website/{id} or PATCH /website/{id}

  - Updates the specified website.

  - Body parameters:

    ```
    {
      "name": "Updated Name",
      "url": "https://updated-url.com"
    }
    ```
  - Returns a success message in JSON.
  
- ### DELETE /website/{id}

  - Deletes the specified website and all related monitoring data.

  - Returns JSON with a confirmation message.

## Installation
### Clone the repository
  ```
  git clone https://github.com/Hasan-Alherek/IsIt200.git
  cd IsIt200/backend
  
  ```
### Install dependencies
  ```
  composer install
  
  ```
### Copy environment file and configure
  ```
  cp .env.example .env
  
  ```
Edit .env to set up database, queue, etc.

### Run migrations
  ```
  php bin/console doctrine:migrations:migrate
  
  ```
### Start the scheduler (for periodic checks)
```
php bin/console scheduler:run

```
### Start the worker (for async message handling)
  ```
  php bin/console messenger:consume
  
  ```
## Recommended: Use Supervisor

It’s recommended to use Supervisor
to keep the Symfony process running continuously in the background.

Example Supervisor config (/etc/supervisor/conf.d/isit200.conf):

```
[program:isit200-worker]
command=/usr/bin/php /var/www/IsIt200/bin/console messenger:consume
directory=/var/www/IsIt200
autostart=true
autorestart=true
stderr_logfile=/var/log/IsIt200-worker.err.log
stdout_logfile=/var/log/IsIt200-worker.out.log
```
Then run:
```
service supervisor start
```
  
```
sudo supervisorctl reread
```
  
```
sudo supervisorctl update
```
  
```
sudo supervisorctl start isit200-worker
```

## Example Use Case

- Monitor uptime and response time for your sites and APIs.

- Get quick insights when an endpoint goes down.

- Use this as a backend for a monitoring dashboard or alert system.

## License

This project is licensed under the MIT License.
