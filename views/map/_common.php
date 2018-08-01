<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::$app->name;
?>
<div id="sidebar" class="sidebar collapsed">
    <!-- Nav tabs -->
    <div class="sidebar-tabs">
        <ul role="tablist">
            <li><a href="#home" role="tab"><i class="glyphicon glyphicon-menu-hamburger"></i></a></li>
        </ul>

        <ul role="tablist">
            <li><a href="#account" role="tab"><i class="glyphicon glyphicon-user"></i></a></li>
            <li><a href="#faq" role="tab"><i class="glyphicon glyphicon-question-sign"></i> </a></li>
        </ul>
    </div>

    <!-- Tab panes -->
    <div class="sidebar-content">
        <div class="sidebar-pane" id="home">
            <h1 class="sidebar-header"><?= Yii::$app->name ?><span class="sidebar-close"><i
                            class="glyphicon glyphicon-menu-left"></i></span></h1>
            <p>
                Welcome to the <a href="/"><strong><?= Yii::$app->name ?></strong></a> site.
            </p>
            <p>
                Here you can plan your route by drawing it on the map.
                Download the result in few GPS-formats such as GPX and KML, and share
                their work with friends.
            </p>

        </div>

        <div class="sidebar-pane" id="account">
            <h1 class="sidebar-header">User account<span class="sidebar-close"><i
                            class="glyphicon glyphicon-menu-left"></i></span></h1>
            <?php if (Yii::$app->user->isGuest): ?>
                <p>
                    <?= Html::a('Login', ['user/login']) ?><br>
                    <small>
                        Log in to your account for continue working on previously created data.
                    </small>
                </p>

                <p>
                    <?= Html::a('Sign up', ['user/signup']) ?><br>
                    <small>
                        Go through a simple registration procedure for get more features.
                    </small>
                </p>

                <p>
                    <?= Html::a('Reset password', ['user/request-password-reset']) ?><br>
                    <small>
                        A simple procedure will allow you to recovery control of your account.
                    </small>
                </p>
            <?php else: ?>
                <p>
                    <?= Html::a('My profile', ['user/index']) ?><br>
                    <small>
                        Open your profile.
                    </small>
                </p>
                <p>
                    <?= Html::a('Logout (' . Yii::$app->user->identity->username . ')', ['/user/logout'], ['data-method' => 'POST']) ?>
                </p>
            <?php endif ?>
        </div>

        <div class="sidebar-pane" id="faq">
            <h1 class="sidebar-header">F.A.Q<span class="sidebar-close"><i
                            class="glyphicon glyphicon-menu-left"></i></span></h1>
            <?= \app\widgets\Faq::widget() ?>
        </div>
    </div>
    <input type="file" accept=".gpx,.kml" id="open-file" name="file" class="sidebar-pane-file">
</div>
<?= $this->render('_save-data-modal') ?>
<?= $this->render('_download-data-modal') ?>

