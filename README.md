# Money Transfer API

## About

A simple RESTful API to send and receive money

- Simple;
- Configurable;
- With notifications;
- Testable;
- Made with Laravel 8;
- Open source.

## Stack

MySQL 8, PHP 8, Laravel 9.

## Requirements to local run

* Docker;
* Docker Compose;
* External API services to:
    * Authorize transfers: `CONSULT_EXTERNAL_API_SERVICE`;
    * Notify users about transfers: `NOTIFICATION_USER_API_SERVICE`'.

## Installation

* Clone or download this repository;
* In your terminal run: `docker-compose up -d`;
* Rename `.env.example` to `.env`
* In your terminal execute the commands:
    * `docker exec -it transfer-api composer install`
    * `docker exec -it transfer-api php artisan key:generate`
    * `docker exec -it transfer-api php artisan migrate:fresh --seed`

## Test

Just run `docker exec -it transfer-api composer test`

## How to execute

#### Make a new transfer

`POST`
```sh
http://localhost:8000/api/v1/users/transfer
```
**header:** `Content-Type: application/json`

`body`
```js
{
    "value": "100.00",
    "payer": "0730a5e4-89a2-4dd9-8d31-ab6344b0ba60",
    "payee": "0a35e361-873a-4075-8615-643fa1297fc0"
}
```

#### List Users

`GET`
```sh
http://localhost:8000/api/v1/users
```

#### Show User
Users Ids are UUID, for example:`0a35e361-873a-4075-8615-643fa1297fc0`

`GET`
```sh
http://localhost:8000/api/v1/users/{userId}
```

##

### Todo list

- [ ] More tests;
- [ ] GitHub Actions;
- [ ] New endpoints;
- [ ] ......
