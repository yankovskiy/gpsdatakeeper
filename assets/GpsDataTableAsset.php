<?php
namespace app\assets;


use yii\web\AssetBundle;
/**
 * Class GpsDataTableAsset
 *
 * Asset bundle for table  my gps data in user profile
 * @package app\assets
 */
class GpsDataTableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/tokml.min.js',
        'js/togpx.min.js',
        'js/gpsdatahelper.min.js',
    ];
}