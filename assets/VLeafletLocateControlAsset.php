<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class VLeafletLocateControlAsset
 *
 * Asset bundle from vendor for Leaflet widget
 * @see \app\widgets\Leaflet
 * @package app\assets
 */
class VLeafletLocateControlAsset extends AssetBundle
{
    public $sourcePath = '@npm/leaflet.locatecontrol/dist';

    public $css = [
        'L.Control.Locate.css',
    ];

    public $js = [
        'L.Control.Locate.min.js',
    ];

    public $depends = [
        'app\assets\VLeafletAsset',
    ];
}