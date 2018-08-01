<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Sign In';

?>

<div class="login-box">
    <div class="login-logo">
        <?= Html::a(Yii::$app->name, '/') ?>
    </div>

    <div class="login-box-body">
        <?= Alert::widget(['alertTypes' => ['success' => 'alert-success-old']]) ?>
        <p class="login-box-msg">Sign in to start your session</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
            ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form
            ->field($model, 'password', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
            ])
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>

            <div class="col-xs-4">
                <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>


        <?php ActiveForm::end(); ?>


        <a href="request-password-reset">I forgot my password</a><br>
        <a href="signup" class="text-center">Register a new membership</a>
    </div>

</div>
