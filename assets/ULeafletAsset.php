<?php

namespace app\assets;


use yii\web\AssetBundle;

/**
 * Class ULeafletAsset
 *
 * Asset bundle for Leaflet widget
 * @see \app\widgets\Leaflet
 * @package app\assets
 */
class ULeafletAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $js = [
        'js/leaflet-sidebar.min.js',
        'js/tokml.min.js',
        'js/togpx.min.js',
        'js/uleaflet.min.js',
    ];

    public $css = [
        'css/leaflet-sidebar.min.css',
    ];

    public $depends = [
        'app\assets\LeafletAsset',
    ];
}