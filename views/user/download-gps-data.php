<?php
/* @var $model \app\models\DownloadGspsDataForm */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin(['id' => 'download-gps-data-form']) ?>
<?= $form->field($model, 'format')->dropDownList(['GPX' => 'GPX', 'KML' => 'KML']) ?>
<?= $form->field($model, 'fileName') ?>
<div class="form-group">
    <?= Html::submitButton('Download', ['class' => 'btn btn-primary', 'name' => 'download-button']) ?>
</div>
<?php ActiveForm::end(); ?>
