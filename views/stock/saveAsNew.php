<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Stock */

$this->title = 'Save As New Stock: '. ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stock', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Save As New';
?>
<div class="stock-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
