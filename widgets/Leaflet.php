<?php

namespace app\widgets;
use app\assets\LeafletAsset;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class Leaflet
 *
 * Widget for display and interaction with map.
 * @package app\widgets
 */
class Leaflet extends Widget
{
    /**
     * Client options for init map
     * @var array
     */
    public $options = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $view = $this->getView();
        LeafletAsset::register($view);
        $this->initJs($view);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return Html::tag('div', '',
            [
                'style' => 'height: 100%',
                'id' => 'map',
            ]
        );
    }

    /**
     * Returns the processed js options
     * @return mixed
     */
    public function getOptions()
    {
        $options = [];
        foreach ($this->options as $key => $option) {
            $options[$key] = $option;
        }
        return empty($options) ? '{}' : Json::encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }

    /**
     * Inits JS code
     * @param \yii\web\View $view
     */
    private function initJs($view)
    {
        $options = $this->getOptions();

        $view->registerJs("u_init($options);");
    }
}