<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class VLeafletEasyButtonAsset
 *
 * Asset bundle from vendor for Leaflet widget
 * @see \app\widgets\Leaflet
 * @package app\assets
 */
class VLeafletEasyButtonAsset extends AssetBundle
{
    public $sourcePath = '@npm/leaflet-easybutton/src';

    public $css = [
        'easy-button.css',
    ];

    public $js = [
        'easy-button.js',
    ];

    public $depends = [
        'app\assets\VLeafletAsset',
    ];
}