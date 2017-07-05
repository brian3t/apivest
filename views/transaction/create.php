<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Create Transaction';
$this->params['breadcrumbs'][] = ['label' => 'Transaction', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header"><h3>Buy/Sell Stock</h3></section>
<section class="content">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</section>

<?php
$this->registerJsFile('/js/transaction_create.js',['position'=>$this::POS_END]);