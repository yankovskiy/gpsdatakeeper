<?php

/* @var $this yii\web\View */

$this->title = 'F.A.Q.';
?>
<div class="row">
    <div class="col-md-5">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Frequently asked questions</h3>
            </div>
            <div class="box-body">
                <?= \app\widgets\Faq::widget(['isBackend' => true]) ?>
            </div>
        </div>
    </div>
</div>