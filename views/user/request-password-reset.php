<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

/* @var $model \app\models\PasswordResetRequestForm */

/* @var $isAjax bool */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\widgets\Alert;

?>
<div class="login-box">
    <?php if (!$isAjax): ?>
        <div class="login-logo">
            <?= Html::a(Yii::$app->name, '/') ?>
        </div>
    <?php endif; ?>

    <div class="login-box-body">
        <?php if (Yii::$app->session->hasFlash('success') || Yii::$app->session->getFlash('error')): ?>
            <?= Alert::widget(
                [
                    'alertTypes' =>
                        [
                            'success' => 'alert-success-old',
                            'error' => 'alert-danger',
                        ],
                    'closeButton' => false
                ]) ?>
        <?php else: ?>

            <p class="login-box-msg">Enter your mail for reset password</p>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form', 'enableClientValidation' => false]); ?>

            <?= $form
            ->field($model, 'email', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
            ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>

            <div class="row">
                <div class="col-xs-4">
                    <?= Html::submitButton('Reset', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        <?php endif; ?>
    </div>

</div>