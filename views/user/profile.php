<?php

/* @var $this \yii\web\View */


use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\widgets\Alert;

$this->title = 'Profile'; ?>
<div class="row">
    <div class="col-md-5">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Change user password</h3>
            </div>

            <?php $form = ActiveForm::begin(['id' => 'change-password-form', 'enableClientValidation' => false]); ?>
            <div class="box-body">
                <?= Alert::widget() ?>
                <?= $form
                    ->field($model, 'password', [
                        'options' => ['class' => 'form-group has-feedback'],
                        'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
                    ])
                    ->label(false)
                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
            </div>
            <div class="box-footer">
                <?= Html::submitButton('Change', ['class' => 'btn btn-primary', 'name' => 'change-password-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>