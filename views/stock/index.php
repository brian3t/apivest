<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = 'Stock';
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<section class="content-header">
    <h1>List of Stocks</h1>
</section>
<section class="content">
    <div class="stock-index">
        <p>
            <?= Html::a('Create Stock', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?php
        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            'symbol',
            'name',
            'last_sale',
            'last_sale_text',
            'market_cap',
            'ipo_year_text',
            'ipo_year',
            'sector',
            'industry',
            'summary_quote',
            'exchange',
            'country',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{save-as-new} {view} {update} {delete}',
                'buttons' => [
                    'save-as-new' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-copy"></span>', $url, ['title' => 'Save As New']);
                    },
                ],
            ],
        ];
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumn,
            'pjax' => true,
            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-stock']],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
            ],
            'export' => false,
            // your toolbar can include the additional full export menu
            'toolbar' => [
                '{export}',
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'target' => ExportMenu::TARGET_BLANK,
                    'fontAwesome' => true,
                    'dropdownOptions' => [
                        'label' => 'Full',
                        'class' => 'btn btn-default',
                        'itemsBefore' => [
                            '<li class="dropdown-header">Export All Data</li>',
                        ],
                    ],
                    'exportConfig' => [
                        ExportMenu::FORMAT_PDF => false
                    ]
                ]),
            ],
        ]); ?>

    </div>
</section>