<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\SignupForm */
/* @var $isAjax bool */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="register-box">
    <?php if(!$isAjax): ?>
    <div class="register-logo">
        <?= Html::a(Yii::$app->name, '/') ?>
    </div>
    <?php endif;?>

    <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>

        <?php $form = ActiveForm::begin(['id' => 'register-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
            ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form
            ->field($model, 'email', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
            ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>

        <?= $form
            ->field($model, 'password', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
            ])
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        <?php if(!$isAjax): ?>
        <br><a href="login">I already have a membership</a>
        <?php endif; ?>
    </div>
    <!-- /.form-box -->
</div>
