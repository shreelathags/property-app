# Property System

## Prerequisites
php 7.4
npm 6.14.17
node v14.21.1 (https://github.com/nvm-sh/nvm) 
composer

Run
`composer install`
`npm install`
`npm run dev`

Create `.env` file from `.env.example` provided. Update the following variables in `.env`
```
PROPERTY_API_HOST=
PROPERTY_API_URL=
PROPERTY_API_KEY=
```

## Usage
`php artisan serve`

## Testing

Prerequisites:
In file `database/migrations/2022_11_11_190933_create_properties_table.php`, comment line #43 before running tests.
SQLite does not support fulltext indexes, so this line needs to be commented for feature tests to work.
Rememeber to uncomment this line after the test

Run
`./vendor/bin/phpunit`
