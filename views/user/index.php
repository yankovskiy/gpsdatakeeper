<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\GpsDataSearch */

/* @var $dataProvider \yii\data\ActiveDataProvider */

use lo\widgets\modal\ModalAjax;
use yii\grid\GridView;
use yii\helpers\Html;

\app\assets\GpsDataTableAsset::register($this);

$this->title = 'My GPS data'; ?>

<?php \yii\widgets\Pjax::begin(['id' => 'grid-pjax', 'timeout' => 2500, 'enablePushState' => false]) ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'title',
        'created_at:datetime',
        'updated_at:datetime',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {download} {delete}',
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action == 'view') {
                    return ['map/view', 'token' => $key];
                } else if ($action == 'delete') {
                    return ['user/delete-gps-data', 'token' => $key];
                } else if ($action == 'download') {
                    return ['user/download-gps-data', 'token' => $key];
                }
            },
            'buttons' => [
                'download' => function($url, $model, $key) {
                    return Html::a('<span class="fa fa-download"></span>', $url, [
                        'title' => 'Download GPS-data',
                        'class' => 'download-gps-data',
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-method' => 'post',
                        'data-pjax' => '#grid-pjax',
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    ]);
                },
            ],
        ],
    ],
]); ?>
<?php \yii\widgets\Pjax::end(); ?>

<?= ModalAjax::widget([
    'id' => 'downloadGpsData',
    'selector' => 'a.download-gps-data',
    'options' => ['class' => 'header-primary'],
    'events' => [
        ModalAjax::EVENT_MODAL_SUBMIT => new \yii\web\JsExpression("
        function(event, data, status, xhr, selector) {
                if(status){
                    downloadGpsData(data);
                    $(this).modal('toggle');                
                }
        }
        "),
    ],
]); ?>