<p style="text-align: center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px" alt="">
    </a>
    <h1 style="text-align: center">Yii2 Weather Forecast</h1>
    <br />



INSTALLATION
------------

Clone reepository
```
git clone https://github.com/cusursuz/weather_forecast.git
cd ./weather_forecast
```
Run the installation
```
docker-compose run --rm backend composer install
```

Run init
```
docker-compose run --rm backend php /app/init
```

Start the container
```
docker-compose up -d
```

Setup db connection in `common/config/main-local.php` file

```
'components' => [
    ...
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'pgsql:host=pgsql;port=5432;dbname=forecast',
        'username' => 'forecast',
        'password' => 'weather',
        'charset' => 'utf8',
    ],
```
Run migrations

```
php yii migrate
```
Get API key `https://home.openweathermap.org/api_keys`

Setup `$appid=''` for API key in `console/controllers/PopulateForecastController.php` file

Populate DB with data
---------------------
Get forecast data for your city
```
php yii populate-forecast --country=Country --city=City
```

Run the following command to update all weather forecasts.
```
php yii populate-forecast/update
```

View results
------------
You can then access the application through the following URL:
```
http://127.0.0.1:20080
```

Admin panel
-----------
You can then access admin panel through the following URL:
```
http://127.0.0.1:21080
```
```
Signup and set user.status=10 in the Database to gain access.
```