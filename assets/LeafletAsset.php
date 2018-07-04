<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class LeaflsetAssets
 *
 * Asset bundle from vendor for Leaflet widget
 * @see \app\widgets\Leaflet
 * @package app\assets
 */
class LeafletAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $css = [
        'leaflet/dist/leaflet.css',
        'leaflet.draw/dist/leaflet.draw.css',
        'leaflet.locatecontrol/dist/L.Control.Locate.css',
    ];

    public $js = [
        'leaflet/dist/leaflet.js',
        'leaflet.draw/dist/leaflet.draw.js',
        'leaflet.locatecontrol/dist/L.Control.Locate.min.js',
    ];

}