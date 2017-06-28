<?php

/* @var $this yii\web\View */

$this->title = 'VestChalleng Admin';
$user = Yii::$app->user->identity;
/** @var \app\models\User $user */

?>
<section class="content-header"><h3>Dashboard</h3></section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-right box-tools">
                        <button class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="box-title">User's portfolio:</div>
                </div>
                <!--                    --><? //= $user->portfolio_html ?>
                <div class="box-body">AAPL 5 stocks</div>
            </div>
        </div>
    </div>
</section>