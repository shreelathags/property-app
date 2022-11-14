# Property System

## Prerequisites
php 7.4

npm 6.14.17

node v14.21.1 (https://github.com/nvm-sh/nvm) 

composer

Create `.env` file from `.env.example` provided. Update the following variables in `.env`
```
PROPERTY_API_HOST=
PROPERTY_API_URL=
PROPERTY_API_KEY=
```

### Run

`composer install`

`npm install`

`npm run dev`

`php artisan migrate`

## Usage
#### Task 1 - To pull the property details from the API - 

`php artisan app:sync-property-data`

`php artsian queue:work`

#### Task 2 - To start UI:

`php artisan serve`

#### Task 3 - API for agent summary: 

GET `api/agents/summary`

Parameters:
`page[offset]`
`page[limit]`

Example:

![Screenshot 2022-11-14 at 12 20 31](https://user-images.githubusercontent.com/28144154/201658705-9a5e6ae6-c2c9-4812-b3b7-17b3e7a7f707.png)


#### Task 4 - SQL to find agents who have competing agents

```
SELECT a.first_name, a.last_name
FROM
(
    SELECT ap1.agent_id agent1, ap2.agent_id agent2, ap1.property_id property
    FROM agents_properties ap1
    INNER JOIN agents_properties ap2
    ON ap1.property_id = ap2.property_id 
    WHERE ap1.agent_id != ap2.agent_id
    GROUP BY ap1.agent_id, ap2.agent_id
    HAVING COUNT(*) > 1
)app
LEFT JOIN
agents a 
ON app.agent1=a.id
GROUP BY app.agent1
HAVING COUNT(app.agent1) > 1
```

## Testing

Prerequisites:
In file `database/migrations/2022_11_11_190933_create_properties_table.php`, comment line #43 before running tests.
SQLite does not support fulltext indexes, so this line needs to be commented for feature tests to work.
Rememeber to uncomment this line after the test

Run

`./vendor/bin/phpunit`
