<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\ForecastSearch $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $date */
?>
<?php
$this->registerLinkTag([
    'rel' => 'stylesheet',
    'href' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css',
]);
?>
<div class="weather-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php echo Html::beginTag('div', ['class' => 'card']); ?>

    <?php echo Html::beginTag('div', ['class' => 'card-header']); ?>
    <?= Html::label('Search') ?>
    <?= Html::endTag('div'); ?>

    <?= Html::beginTag('div', ['class' => 'card-body']); ?>

        <?= Html::beginTag('div', ['class' => 'row g-3']); ?>

            <?= Html::beginTag('div', ['class' => 'row']); ?>
                <?= Html::beginTag('div', ['class' => 'col-lg-3 col-sm-6']); ?>
                    <?= Html::label('Start', "startDate") ?>
                    <?= Html::input("date", "createdFrom", $value = $date['createdFrom'], $options = ['class' => "form-control"]) ?>
                <?= Html::endTag('div'); ?>

                <?= Html::beginTag('div', ['class' => 'col-lg-3 col-sm-6']); ?>
                    <?= Html::label('End', "endDate") ?>
                    <?= Html::input("date", "createdTo", $value = $date['createdTo'], $options = ['class' => "form-control"]) ?>
                <?= Html::endTag('div'); ?>

                <?= Html::beginTag('div', ['class' => 'col-lg-2 col-sm-3']); ?>
                    <?= Html::label('') ?>
                    <button class="form-control btn btn-success"><i class="bi bi-search"></i> Search</button>
                <?= Html::endTag('div'); ?>

            <?= Html::endTag('div'); ?>
        <?= Html::endTag('div'); ?>
    <?= Html::endTag('div'); ?>

    <?php ActiveForm::end(); ?>

</div>
