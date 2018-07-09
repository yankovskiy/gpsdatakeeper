<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class VLeafletDrawAsset
 *
 * Asset bundle from vendor for Leaflet widget
 * @see \app\widgets\Leaflet
 * @package app\assets
 */
class VLeafletDrawAsset extends AssetBundle
{
    public $sourcePath = '@bower/leaflet.draw/dist';

    public $css = [
        'leaflet.draw.css',
    ];

    public $js = [
        'leaflet.draw.js',
    ];

    public $depends = [
        'app\assets\VLeafletAsset',
    ];
}