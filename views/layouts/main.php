<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/morris.css" rel="stylesheet" type="text/css"/>
    <link href="/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css"/>
    <link href="/css/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <script src="/js/respond.min.js"></script>
    <![endif]-->

</head>
<body class="skin-blue fixed">
<?php $this->beginBody() ?>

<header class="header">
    <a href="/" class="logo">
        VestChallenge API
    </a>
    <?php
    NavBar::begin([
        'brandLabel' => 'Vest Challenge API',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-static-top',
        ],
    ]);
    $menu_items = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menu_items = array_merge($menu_items, [
            ['label' => 'Sign in', 'url' => ['/user/security/login']],
            ['label' => 'Sign Up', 'url' => '/user/registration/register']
        ]);
    } else {
        $menu_items = array_merge($menu_items, [
            ['label' => 'Sign out (' . Yii::$app->user->identity->username . ')',
                'url' => ['/user/security/logout'],
                'linkOptions' => ['data-method' => 'post']],
            ['label' => 'Register', 'url' => ['/user/registration/register'], 'visible' => Yii::$app->user->isGuest]]);
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menu_items,
    ]);
    echo '<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>';
    NavBar::end();
    ?>
</header>
<header class="header">
    <a href="index.html" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        AdminLTE
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <form id="logout" action="/user/security/logout" method="post"><input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" /></form>
                <li class="logout">
                    <a href="#" id="logout_a" onclick="$('#logout').submit()">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                </div>
                <div class="pull-left info">
                    <p>Hello, <?= (is_object(Yii::$app->user->identity)?Yii::$app->user->identity->username:'Guest') ?></p>

                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
<!--            <form action="#" method="get" class="sidebar-form">-->
<!--                <div class="input-group">-->
<!--                    <input type="text" name="q" class="form-control" placeholder="Search..."/>-->
<!--                    <span class="input-group-btn">-->
<!--                                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>-->
<!--                            </span>-->
<!--                </div>-->
<!--            </form>-->
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="active">
                    <a href="/">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/stock">
                        <i class="fa fa-bar-chart-o"></i> <span>Stock</span>
                    </a>
                </li>
                <li>
                    <a href="/transaction/indexown">
                        <i class="fa fa-handshake-o"></i> <span>Buy/Sell Stock</span>
                    </a>
                </li>
                <?php if (Yii::$app->user->identity->is_admin):?>
                <li>
                    <a href="/transaction/index">
                        <i class="fa fa-handshake-o"></i> <span>All transactions</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Breadcrumb here

        -->
    <aside class="right-side">
        <?= $content ?>
    </aside>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left"></p>

        <p class="pull-right">&copy; 2017 VestChallenge Pte. Ltd.</p>
    </div>
</footer>

<?php $this->endBody() ?>
<script src="/js/raphael.min.js"></script>
<script src="/js/morris.min.js"></script>
<script src="/js/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="/js/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="/js/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<script src="/js/moment.min.js" type="text/javascript"></script>
<script src="/js/lodash.core.min.js" type="text/javascript"></script>
<script src="/js/fullcalendar.min.js" type="text/javascript"></script>
<script src="/js/jquery.knob.min.js" type="text/javascript"></script>
<script src="/js/icheck.min.js" type="text/javascript"></script>
<script src="/js/AdminLTE/app.js" type="text/javascript"></script>


</body>
</html>
<?php $this->endPage() ?>
