<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

$this->title = 'Transaction';
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});";
$this->registerJs($search);

$user_ddl = ArrayHelper::map(\app\models\User::find()->select('id, username')->asArray()->all(),'id','username');
?>

<section class="content-header"><h3>Transaction</h3>
    <p>
        <?= Html::a('Advanced Search', '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form container-fluid" style="display:block">
        <div class="col-sm-6 col-xs-12">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>

        <?= Html::a('Buy/Sell Stock', ['create'], ['class' => 'btn btn-success']) ?>
        <?php
        /*echo Select2::widget([
            'name' => 'user_id',
            'data' => $user_ddl,
            'options' => ['placeholder' => 'Select user'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);*/
        ?>
    </p></section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <?php
                $gridColumn = [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'class' => 'kartik\grid\ExpandRowColumn',
                        'width' => '50px',
                        'value' => function ($model, $key, $index, $column) {
                            return GridView::ROW_COLLAPSED;
                        },
                        'detail' => function ($model, $key, $index, $column) {
                            return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
                        },
                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                        'expandOneOnly' => true
                    ],
                    ['attribute' => 'id', 'visible' => false],
                    [
                        'attribute' => 'user_id',
                        'label' => 'User',
                        'value' => function ($model) {
                            return $model->user->username;
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->asArray()->all(), 'id', 'username'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid--user_id']
                    ],
                    [
                        'attribute' => 'stock_id',
                        'label' => 'Stock',
                        'value' => function ($model) {
                            return $model->stock->symbol . ' - ' . $model->stock->name;
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \yii\helpers\ArrayHelper::map(\app\models\Stock::find()->asArray()->all(), 'id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Stock', 'id' => 'grid--stock_id']
                    ],
                    [
                        'attribute' => 'is_buying',
                        'label' => 'Bought or Sold',
                        'value' => function ($model) {
                            return $model->is_buying ? 'Bought' : 'Sold';
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \yii\helpers\ArrayHelper::map(\app\models\Stock::find()->asArray()->all(), 'id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Stock', 'id' => 'grid--stock_id']
                    ],
                    'created_at',
                    ['label' => 'Bought For',
                        'value' => function ($model) {
                            return ($model->is_buying ? $model->unit_cost : '-');
                        }],
                    ['label' => 'Sold For',
                        'value' => function ($model) {
                            return ($model->is_buying ? '-' : $model->unit_cost);
                        }],
                    ['label' => 'Initially Bought For',
                        'value' => function ($model) {
                            /** @var \app\models\Transaction $model */
                            if ($model->is_buying) {
                                return '-';
                            }
                            return $model->last_txn_same_stock ? $model->last_txn_same_stock->unit_cost : '-';
                        }],
                    ['attribute' => 'profit',
                        'label' => 'Value Gained',
                        'value' => function ($model) {
                            /** @var \app\models\Transaction $model */
                            if ($model->is_buying) {
                                return '-';
                            }
                            if (!$model->last_txn_same_stock) {
                                return '-';
                            }
                            $gain = $model->gain;
                            $percent = $model->points_gained;
                            return $gain . " ($percent%) | " . round($percent) . " points";//calculate difference of value when sold and value when initially bought
                        }],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{save-as-new} {view} {update} {delete}',
                        'buttons' => [
                            'save-as-new' => function ($url) {
                                return Html::a('<span class="glyphicon glyphicon - copy"></span>', $url, ['title' => 'Save As New']);
                            },
                        ],
                    ],
                    ['label' => 'Description', 'format' => 'html', 'value' => function ($model) {
                        if ($model->is_buying) {
                            return 'You bought this stock for ' . $model->unit_cost;
                        }
                        $last_txn = $model->last_txn_same_stock;
                        if (!$last_txn) {
                            return "You sold this stock for $" . $model->unit_cost;
                        }
                        $gain = $model->unit_cost - $last_txn->unit_cost;
                        $percent = round($gain / $model->last_txn_same_stock->unit_cost * 100, 2);
                        $res = "You sold this stock for $" . $model->unit_cost . "<br/> On " . $last_txn->created_at . " you bought it for $" . $last_txn->unit_cost . "<br/>";
                        if ($gain > 0) {
                            $res .= "You gained $$gain dollar, or $percent% money value for doing so <br/>" . round($percent) . " points earned";
                        } else {
                            $gain = 0 - $gain;
                            $percent = 0 - $percent;
                            $res .= "You lost $$gain dollar, or $percent% money value for doing so <br/>" . round($percent) . " points lost";
                        }
                        return $res;
                    }]
                ];
                ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'pjax' => true,
                    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-transaction']],
                    'panel' => [
                        'type' => GridView::TYPE_PRIMARY,
                        'heading' => '<span class="glyphicon glyphicon - book"></span>  ' . Html::encode($this->title),
                    ],
                    'export' => false,
                    // your toolbar can include the additional full export menu
                    'toolbar' => [
                        /*'{export}',
                        ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $gridColumn,
                            'target' => ExportMenu::TARGET_BLANK,
                            'fontAwesome' => true,
                            'dropdownOptions' => [
                                'label' => 'Full',
                                'class' => 'btn btn-default',
                                'itemsBefore' => [
                                    '<li class="dropdown - header">Export All Data</li>',
                                ],
                            ],
                            'exportConfig' => [
                                ExportMenu::FORMAT_PDF => false
                            ]
                        ]),*/
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</section>