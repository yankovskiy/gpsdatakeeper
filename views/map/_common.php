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
            <li><a href="#actions" role="tab"><i class="glyphicon glyphicon-folder-open"></i></a></li>
        </ul>

        <ul role="tablist">
            <li><a href="#account" role="tab"><i class="glyphicon glyphicon-user"></i></a></li>
            <li><a href="#faq" role="tab"><i class="glyphicon glyphicon-question-sign"></i> </a> </li>
        </ul>
    </div>

    <!-- Tab panes -->
    <div class="sidebar-content">
        <div class="sidebar-pane" id="home">
            <h1 class="sidebar-header"><?= Yii::$app->name ?><span class="sidebar-close"><i class="glyphicon glyphicon-menu-left"></i></span></h1>
            <p>
                Welcome to the <a href="/"><strong><?= Yii::$app->name ?></strong></a> site.
            </p>
            <p>
                Here you can plan your route by drawing it on the map.
                Download the result in few GPS-formats such as GPX and KML, and share
                their work with friends.
            </p>

        </div>

        <div class="sidebar-pane" id="actions">
            <h1 class="sidebar-header">Map actions<span class="sidebar-close"><i class="glyphicon glyphicon-menu-left"></i></span></h1>
            <p>
                <a href="#" id="download-gpx">Download GPX</a><br>
                <small>
                    Download GPX-file for future use in many navigators and programs.
                </small>
            </p>

            <p>
                <a href="#" id="download-kml">Download KML</a><br>
                <small>
                    Download KML-file for future use in such programs as
                    Google Earth, Google Maps, and Google Maps for mobile devices.
                </small>
            </p>

            <p>
                <label for="open-file" class="sidebar-pane-label">Open file</label><br>
                <input type="file" accept=".gpx,.kml" id="open-file" name="file" class="sidebar-pane-file">
                <small>
                    Open your gps-data for view on the map. Supported GPX and KML formats.
                </small>
            </p>

            <p class="save-block">
                <a href="#" id="save-to-server">Save to server</a><br>
                <small>
                    Save the map on the server to share it with a friend or continue working on it in
                    further.
                </small>
            </p>
        </div>

        <div class="sidebar-pane" id="account">
            <h1 class="sidebar-header">User account<span class="sidebar-close"><i class="glyphicon glyphicon-menu-left"></i></span></h1>
            <?php if(Yii::$app->user->isGuest): ?>
                <p>
                    <?= Html::a('Login', ['user/login'], ['class' => 'account-actions', 'title' => 'Login'])?><br>
                    <small>
                        Log in to your account for continue working on previously created data.
                    </small>
                </p>

                <p>
                    <?= Html::a('Sign up', ['user/signup'], ['class' => 'account-actions', 'title' => 'Sign up'])?><br>
                    <small>
                        Go through a simple registration procedure for get more features.
                    </small>
                </p>

                <p>
                    <?= Html::a('Reset password', ['user/request-password-reset'], ['class' => 'account-actions', 'title' => 'Reset password'])?><br>
                    <small>
                        A simple procedure will allow you to recovery control of your account.
                    </small>
                </p>
            <?php else:?>
                <p>
                    <?= Html::a('My profile', ['user/index']) ?><br>
                    <small>
                        Open your profile.
                    </small>
                </p>
                <p>
                    <?= Html::a('Logout (' . Yii::$app->user->identity->username . ')', ['/user/logout'], ['data-method' => 'POST']) ?>
                </p>
            <?php endif?>
        </div>

        <div class="sidebar-pane" id="faq">
            <h1 class="sidebar-header">F.A.Q<span class="sidebar-close"><i class="glyphicon glyphicon-menu-left"></i></span></h1>
            <?= \app\widgets\Faq::widget() ?>
        </div>
    </div>
</div>

<?= $this->render('_save-data-modal') ?>

<?= \lo\widgets\modal\ModalAjax::widget([
    'id' => 'accountActions',
    'selector' => 'a.account-actions',
    'options' => ['class' => 'header-primary'],
    'autoClose' => true,
]) ?>

