<?php

declare(strict_types=1);

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Weather;

/**
 * ForecastSearch represents the model behind the search form of `common\models\Weather`.
 */
class ForecastSearch extends Weather
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
            [['datetime', 'temp_avg', 'countryName', 'cityName'], 'safe'],
            [['temp_min', 'temp_max'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
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
        $query = Weather::find()
            ->joinWith(['city.country', 'city'])   //->with('city')->with('city.country')
            ->select([
                'city_id',
                'MAX(temp_max) as temp_max',
                'MIN(temp_min) as temp_min',
                'ROUND(AVG(temp_avg), 2) as temp_avg',
            ])
            ->groupBy(['city_id', 'city.name', 'country.name']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'temp_max',
                'temp_min',
                'countryName' => [
                    'asc' => ['country.name' => SORT_ASC],
                    'desc' => ['country.name' => SORT_DESC],
                ],
                'cityName' => [
                    'asc' => ['city.name' => SORT_ASC],
                    'desc' => ['city.name' => SORT_DESC],
                ],
                'temp_avg'
            ],
        ]);

        return $dataProvider;
    }

    public function searchHistory($params): ActiveDataProvider
    {
        $query = Weather::find()
            ->with(['city', 'city.country'])
            ->select([
                'city_id',
                'MAX(temp_max) as temp_max',
                'MIN(temp_min) as temp_min',
                'ROUND(AVG(temp_avg), 2) as temp_avg',
            ])
            ->where(['city_id' => $params['id']]);

        if (
            isset($params['createdFrom'])
            && $params['createdFrom'] != null
            && isset($params['createdTo'])
            && $params['createdTo'] != null
        ) {
            $query->andWhere(['>=', 'datetime', $params['createdFrom']])
                ->andWhere(['<=', 'datetime', $params['createdTo'] . ' 23:59:59'])
                ->orderBy(['datetime' => 'ASC'])
                ->groupBy(['city_id', 'country.name', 'city.name', 'datetime', 'temp_max', 'temp_min']);
        } else {
            $query
                ->orderBy(['datetime' => 'ASC'])
                ->groupBy(['city_id', 'country.name', 'city.name', 'datetime']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'temp_max',
                'temp_min',
                'countryName' => [
                    'asc' => ['country.name' => SORT_ASC],
                    'desc' => ['country.name' => SORT_DESC],
                ],
                'cityName' => [
                    'asc' => ['city.name' => SORT_ASC],
                    'desc' => ['city.name' => SORT_DESC],
                ],
                'temp_avg'
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['city.country', 'city']);
        }

        return $dataProvider;
    }
}
