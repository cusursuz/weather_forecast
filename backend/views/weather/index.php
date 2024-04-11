<?php

declare(strict_types=1);

use common\models\Weather;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\WeatherSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Weathers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weather-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<!--        --><?php //= Html::a('Create Weather', ['create'], ['class' => 'btn btn-success'])?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'countryName',
            'cityName',
            'datetime',
            'temp_min',
            'temp_max',
            //'humidity',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Weather $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ]
    ]); ?>

    <?php Pjax::end(); ?>

</div>
