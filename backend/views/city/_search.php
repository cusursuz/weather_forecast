<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\CitySearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="city-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'country_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'lat') ?>

    <?= $form->field($model, 'lon') ?>

    <?php // echo $form->field($model, 'state')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
