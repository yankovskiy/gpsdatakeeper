<?php

/* @var $this \yii\web\View */

/* @var $googleAuth \app\models\Auth */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


$this->title = 'Profile'; ?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Change user password</h3>
            </div>

            <?php $form = ActiveForm::begin(['id' => 'change-password-form', 'enableClientValidation' => false]); ?>
            <div class="box-body">

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

    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Link social profile</h3>
            </div>

            <div class="box-body">
                <?php if (empty($googleAuth)): ?>
                    <?= Html::a('<i class="fa fa-google-plus"></i> Link account to Google+', ['user/auth', 'authclient' => 'google'], ['class' => 'btn btn-block btn-social btn-google btn-flat']) ?>
                <?php else: ?>
                    <div class="input-group">
                        <?= Html::a('<i class="fa fa-google-plus"></i> Link account to Google+', ['user/auth', 'authclient' => 'google'], ['class' => 'btn btn-block btn-social btn-google btn-flat disabled']) ?>
                        <span class="input-group-btn">
                                <?= Html::a('<i class="fa fa-trash"></i>',
                                    ['user/unlink', 'id' => $googleAuth->id],
                                    [
                                        'class' => 'btn btn-danger',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Are you sure you want to unlink this account?',
                                        'title' => 'Remove link to Google+'
                                    ]) ?>
                        </span>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>