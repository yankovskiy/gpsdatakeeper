<?php
/**
 * Created by PhpStorm.
 * User: ufo
 * Date: 29.06.18
 * Time: 21:46
 */

namespace app\assets;


use yii\web\AssetBundle;

/**
 * Class BackendAsset
 *
 * Asset bundle for user profile
 * @package app\assets
 */
class BackendAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/backend.min.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset',
    ];
}