<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Stock */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Transaction', 
        'relID' => 'transaction', 
        'value' => \yii\helpers\Json::encode($model->transactions),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'UserStockNa', 
        'relID' => 'user-stock-na', 
        'value' => \yii\helpers\Json::encode($model->userStockNas),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="stock-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'symbol')->textInput(['maxlength' => true, 'placeholder' => 'Symbol']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'last_sale')->textInput(['maxlength' => true, 'placeholder' => 'Last Sale']) ?>

    <?= $form->field($model, 'last_sale_text')->textInput(['maxlength' => true, 'placeholder' => 'Last Sale Text']) ?>

    <?= $form->field($model, 'market_cap')->textInput(['maxlength' => true, 'placeholder' => 'Market Cap']) ?>

    <?= $form->field($model, 'ipo_year_text')->textInput(['maxlength' => true, 'placeholder' => 'Ipo Year Text']) ?>

    <?= $form->field($model, 'ipo_year')->textInput(['placeholder' => 'Ipo Year']) ?>

    <?= $form->field($model, 'sector')->textInput(['maxlength' => true, 'placeholder' => 'Sector']) ?>

    <?= $form->field($model, 'industry')->textInput(['maxlength' => true, 'placeholder' => 'Industry']) ?>

    <?= $form->field($model, 'summary_quote')->textInput(['maxlength' => true, 'placeholder' => 'Summary Quote']) ?>

    <?= $form->field($model, 'exchange')->textInput(['maxlength' => true, 'placeholder' => 'Exchange']) ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => true, 'placeholder' => 'Country']) ?>

    <?php
    $forms = [
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('Transaction'),
            'content' => $this->render('_formTransaction', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->transactions),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('UserStockNa'),
            'content' => $this->render('_formUserStockNa', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->userStockNas),
            ]),
        ],
    ];
    echo kartik\tabs\TabsX::widget([
        'items' => $forms,
        'position' => kartik\tabs\TabsX::POS_ABOVE,
        'encodeLabels' => false,
        'pluginOptions' => [
            'bordered' => true,
            'sideways' => true,
            'enableCache' => false,
        ],
    ]);
    ?>
    <div class="form-group">
    <?php if(Yii::$app->controller->action->id != 'save-as-new'): ?>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php endif; ?>
    <?php if(Yii::$app->controller->action->id != 'create'): ?>
        <?= Html::submitButton('Save As New', ['class' => 'btn btn-info', 'value' => '1', 'name' => '_asnew']) ?>
    <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer , ['class'=> 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
