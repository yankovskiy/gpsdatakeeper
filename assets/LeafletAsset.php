<?php

namespace app\assets;


use yii\web\AssetBundle;

/**
 * Class LeafletAsset
 *
 * Asset bundle for Leaflet widget
 * @see \app\widgets\Leaflet
 * @package app\assets
 */
class LeafletAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/leaflet-sidebar.min.js',
        'js/tokml.min.js',
        'js/togpx.min.js',
        'js/uleaflet.min.js',
        'js/togeojson.min.js',
    ];

    public $css = [
        'css/leaflet-sidebar.min.css',
    ];

    public $depends = [
        'app\assets\VLeafletAsset',
        'app\assets\VLeafletDrawAsset',
        'app\assets\VLeafletLocateControlAsset',
    ];
}