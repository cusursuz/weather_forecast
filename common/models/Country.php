<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "country".
 *
 * @property int $id
 * @property string $name
 * @property string $country_code
 *
 * @property City[] $cities
 */
class Country extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'country_code'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['country_code'], 'string', 'max' => 2],
            [['country_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'country_code' => 'Country Code',
        ];
    }

    /**
     * Gets query for [[Cities]].
     *
     * @return ActiveQuery
     */
    public function getCities(): ActiveQuery
    {
        return $this->hasMany(City::class, ['country_id' => 'id']);
    }
}
