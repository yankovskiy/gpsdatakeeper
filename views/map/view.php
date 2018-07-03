<?php
/* @var $this yii\web\View */
/* @var $isGuest bool */
/* @var $isOwner bool */
/* @var $gpsData \app\models\GpsData */
use yii\helpers\Json;
?>

<?= $this->render('_common'); ?>

<?= \app\widgets\Leaflet::widget([
    'options' =>
        [
            'isGuest' => $isGuest,
            'isOwner' => $isOwner,
            'mapCenter' => Json::decode($gpsData->center),
            'mapZoom' => $gpsData->zoom,
            'geoData' => $gpsData->data,
            'gpsDataToken' => $gpsData->token,
            'gpsDataTitle' => $gpsData->title,
        ]
]); ?>

