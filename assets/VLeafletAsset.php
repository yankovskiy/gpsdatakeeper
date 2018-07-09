<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class VLeaflsetAssets
 *
 * Asset bundle from vendor for Leaflet widget
 * @see \app\widgets\Leaflet
 * @package app\assets
 */
class VLeafletAsset extends AssetBundle
{
    public $sourcePath = '@bower/leaflet/dist';

    public $css = [
        'leaflet.css',
    ];

    public $js = [
        'leaflet.js',
    ];

}