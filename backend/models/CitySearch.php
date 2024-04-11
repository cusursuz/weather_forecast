<?php

declare(strict_types=1);

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\City;

/**
 * CitySearch represents the model behind the search form of `common\models\City`.
 */
class CitySearch extends City
{
    public $countryName;
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'country_id'], 'integer'],
            [['name', 'state', 'countryName'], 'safe'],
            [['lat', 'lon'], 'number'],
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
    public function search($params)
    {
        $query = City::find()->joinWith(['country']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'countryName' => [
                    'asc' => ['country.name' => SORT_ASC],
                    'desc' => ['country.name' => SORT_DESC],
                    'label' => 'Country'
                ],
                'name',
                'lat',
                'lon'
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['country']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'country_id' => $this->country_id,
            'lat' => $this->lat,
            'lon' => $this->lon,
        ]);

        if (!empty($this->countryName)) {
            $query->joinWith(['country' => function ($q) {
                $q->where(['ilike', 'country.name', $this->countryName]);
            }]);
        }

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'state', $this->state]);

        return $dataProvider;
    }
}
