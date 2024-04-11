<?php

declare(strict_types=1);

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Weather;

/**
 * WeatherSearch represents the model behind the search form of `common\models\Weather`.
 */
class WeatherSearch extends Weather
{
    public $countryName;
    public $cityName;
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'city_id', 'humidity'], 'integer'],
            [['datetime', 'cityName', 'countryName'], 'safe'],
            [['temp_min', 'temp_max'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = Weather::find()->joinWith(['city', 'city.country']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'cityName' => [
                    'asc' => ['city.name' => SORT_ASC],
                    'desc' => ['city.name' => SORT_DESC],
                    'label' => 'City'
                ],
                'countryName' => [
                    'asc' => ['country.name' => SORT_ASC],
                    'desc' => ['country.name' => SORT_DESC],
                    'label' => 'Country'
                ],
                'datetime',
                'temp_max',
                'temp_min',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['city.country']);
            $query->joinWith(['city']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'city_id' => $this->city_id,
            'datetime' => $this->datetime,
            'temp_min' => $this->temp_min,
            'temp_max' => $this->temp_max,
            'humidity' => $this->humidity,
        ]);


        if (!empty($this->countryName)) {
            $query->joinWith(['city.country' => function ($q) {
                $q->where(['ilike', 'country.name', $this->countryName]);
            }]);
        }
        if (!empty($this->cityName)) {
            $query->joinWith(['city' => function ($q) {
                $q->where(['ilike', 'city.name', $this->cityName]);
            }]);
        }

        return $dataProvider;
    }
}
