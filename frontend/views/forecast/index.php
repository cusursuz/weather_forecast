<?php

declare(strict_types=1);

use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\widgets\HistoryDropdown;

/** @var yii\web\View $this */
/** @var frontend\models\ForecastSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $date */

$this->title = 'Statistics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weather-index">
<?php $this->registerCss("body { background: #efffff; }"); ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?php  echo $this->render('_search', ['model' => $searchModel, 'date' => $date]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'countryName',
            'cityName',
            'temp_min',
            'temp_max',
            'temp_avg',
            [
                'attribute' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) use ($searchModel, $date) {
                    return HistoryDropdown::widget([
                        'cityId' => $model['city_id'],
                        'createdFrom' => $date['createdFrom'],
                        'createdTo' => $date['createdTo'],
                    ]);
                },
            ],
        ],
        'pager' => [
            'class' => LinkPager::class,
        ]
    ]); ?>

    <?php Pjax::end(); ?>

</div>
