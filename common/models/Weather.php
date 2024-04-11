<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "weather".
 *
 * @property int $id
 * @property int $city_id
 * @property string $datetime
 * @property float $temp_min
 * @property float $temp_max
 * @property float $temp_avg
 * @property int $humidity
 * @property City $city
 * @property string $cityName
 * @property string $countryName
 */
class Weather extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'weather';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['city_id', 'datetime', 'temp_min', 'temp_max', 'humidity'], 'required'],
            [['city_id', 'humidity'], 'default', 'value' => null],
            [['city_id', 'humidity'], 'integer'],
            [['datetime'], 'safe'],
            [['temp_min', 'temp_max', 'temp_avg'], 'number'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'cityName' => 'City',
            'countryName' => 'Country',
            'datetime' => 'Date time',
            'temp_min' => 'Min Temperature',
            'temp_max' => 'Max Temperature',
            'temp_avg' => 'Avg Temperature',
            'humidity' => 'Humidity',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * @return string
     */
    public function getCityName(): string
    {
        return $this->city->name;
    }

    /**
     * @return string
     */
    public function getCountryName(): string
    {
        return $this->city->country->name;
    }
}
