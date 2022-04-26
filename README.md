# packt-assignment

## Features

* Laravel 9.7.*
* php ^8.0.2
* Sanctum
* Vue 3
* Node v16.14.2 ||| npm 8.7.0
* API

## Install Dependencies for Development

# Create .env file in 'Packt-backend' folder and copy paste the content from .env.example file
# Update DB configure in .env file as per your local db setup
    * DB_PORT = enter_port_her
    * DB_DATABASE = enter_db_name
    * DB_USERNAME = enter_username
    * DB_PASSWORD = enter_DB_password_here

## Install Dependencies for Development
`Go inside Packt-backend folder and run below-mentioned command`
* `$ composer install`

## Start the MySQL server

## Migrate Database for updating and apply change
this can be run every updating code time
* `$ php artisan migrate`

## After Migrate Database, seed the data for very first time
Seed Data
* `$ php artisan db:seed`

## After Migrate Database, Start the Laravel default server

* `$ php artisan serve`

# Vue Setup

## Go inside 'packt-frontend' folder
Install node modules
* `npm install`

## Inside 'packt-frontend' folder, run below command
* `npm run dev`

# Open Browser
* Go to the URL `http://localhost:3000/register` and register, do login after that with the same registered credentials

## To Run the test cases
* `$ composer run test`

## Note:- For fron-end and back-end app

* Front end App, running on port 3000 which running after `npm run dev` command
* Back end App, running on port 8000 which running after `php artisan serve` command