<?php

declare(strict_types=1);

namespace console\controllers;

use common\models\City;
use common\models\Country;
use common\models\Weather;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\BaseConsole;

class PopulateForecastController extends Controller
{
    private string $appid = '';
    private string $baseApiUrl = "https://api.openweathermap.org/";
    private string $cityApiParams = "geo/1.0/direct?q=%s,%s&limit=1&appid=%s";
    private string $forecastApiParams = "data/2.5/forecast?lat=%s&lon=%s&units=metric&appid=%s";

    public $country;
    public $city;

    /**
     * @param $actionID
     * @return string[]
     */
    public function options($actionID): array
    {
        return ['country', 'city'];
    }

    /**
     * @return string[]
     */
    public function optionAliases(): array
    {
        return ['country' => 'country', 'city' => 'city'];
    }

    /**
     *  Populate weather forecast for the specified city.
     *
     * @return void
     */
    public function actionIndex(): void
    {
        $cities = $this->getApiCities();

        if (count($cities) == 0) {
            echo "Can not find city " . $this->city . "\n";
            $this->stdout("Can not find city $this->city 123 \n", BaseConsole::FG_RED);
            return;
        }

        foreach ($cities as $city) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $country_id = $this->findOrCreateCountry($city->country);
                $city = $this->findOrCreateCity($country_id, $city);
                $this->updateOrCreateWeather($city);
                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
                $this->stdout("Error: can't find $city->name 321\n", BaseConsole::FG_RED);
            }
        }
    }

    /**
     *  Update weather forecast for all cities.
     *
     * @return void
     */
    public function actionUpdate(): void
    {
        $cities = City::find()->all();
        foreach ($cities as $city) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $this->updateOrCreateWeather($city);
                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
                $this->stdout("Error: can't update $city->name \n", BaseConsole::FG_RED);
            }
        }
    }

    /**
     *  Find or create a country.
     *
     * @param string $countryCode
     * @return int
     */
    private function findOrCreateCountry(string $countryCode): int
    {
        $country = Country::findOne(['country_code' => $countryCode]);
        if (!$country) {
            $country = new Country();
            $country->name = $this->country;
            $country->country_code = $countryCode;
            $country->save();
        }
        $this->stdout("Country: $country->name \n", BaseConsole::FG_GREEN);
        return $country->id;
    }

    /**
     * @param int $countryId
     * @param object $cityData
     * @return City|null
     */
    private function findOrCreateCity(int $countryId, object $cityData): ?City
    {
        $city = City::findOne(['country_id' => $countryId, 'name' => $cityData->name]);
        if (!$city) {
            $city = new City();
            $city->country_id = $countryId;
            $city->name = $cityData->name;
            $city->lat = $cityData->lat;
            $city->lon = $cityData->lon;
            $city->state = $cityData->state ?? null;
            $city->save();
        }
        $this->stdout("City: $city->name \n", BaseConsole::FG_GREEN);
        return $city;
    }

    /**
     * @param City $city
     * @return void
     * @throws InvalidConfigException
     */
    private function updateOrCreateWeather(City $city): void
    {
        $weatherData = $this->getApiForecast((float) $city->lat, (float) $city->lon);
        if (!$weatherData) {
            return;
        }

        foreach ($weatherData as $list) {
            $datetime = Yii::$app->formatter->asDatetime($list->dt, 'yyyy-MM-dd HH:mm:ss');
            $weather = Weather::findOne(['city_id' => $city->id, 'datetime' => $datetime]);
            if (!$weather) {
                $weather = new Weather();
                $weather->city_id = $city->id;
            }
            $weather->datetime = $datetime;
            $weather->temp_min = $list->main->temp_min;
            $weather->temp_max = $list->main->temp_max;
            $weather->temp_avg = round(($list->main->temp_max + $list->main->temp_min) / 2, 2);
            $weather->humidity = $list->main->humidity;

            $weather->save();
        }
        $this->stdout("Populated $city->name with weathers data\n", BaseConsole::FG_GREEN);
    }
    /**
     * @return array
     */
    public function getApiCities(): array
    {
        $url = $this->baseApiUrl . $this->cityApiParams;
        $url = sprintf($url, urlencode($this->city), urlencode($this->country), urlencode($this->appid));
        return json_decode(file_get_contents($url));
    }

    /**
     * @param float $lat
     * @param float $lon
     * @return array
     */
    public function getApiForecast(float $lat, float $lon): array
    {
        $url = $this->baseApiUrl . $this->forecastApiParams;
        $url = sprintf($url, $lat, $lon, $this->appid);
        $forecast =  json_decode(file_get_contents($url));
        return $forecast->list;
    }
    public function init(): void
    {
        if ($this->appid === '') {
            $this->stdout("Please setup API key to fetch data\n", BaseConsole::FG_RED);
            die();
        }
    }
}
