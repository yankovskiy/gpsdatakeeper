<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\GpsDataSearch */

/* @var $dataProvider \yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;

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
            'template' => '{view} {delete}',
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action == 'view') {
                    return ['map/view', 'token' => $key];
                } else if ($action == 'delete') {
                    return ['user/delete-gps-data', 'token' => $key];
                }
            },
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-pjax' => '#grid-pjax',
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    ]);
                },
            ],
        ],
    ],
]); ?>
<?php \yii\widgets\Pjax::end(); ?>