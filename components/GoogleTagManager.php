<?php

namespace app\components;

use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\View;

/**
 * Class GoogleTagManager
 *
 * Implements google tag manager for yii.
 * Based on the GoogleTagManager from Oleg aka Ezoterik (https://github.com/ezoterik/yii2-google-tag-manager)
 * @package app\components
 */
class GoogleTagManager extends BaseObject implements BootstrapInterface
{

    /** @var string|null your Google Tag Manager ID */
    public $tagManagerId = null;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if (empty($this->tagManagerId) ||
            !$app->getRequest()->getIsGet() ||
            $app->getRequest()->getIsAjax()) {
            return;
        }

        //Delay attaching event handler to the view component after it is fully configured
        $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
            $app->getView()->on(View::EVENT_BEGIN_BODY, [$this, 'renderCode']);
        });
    }

    /**
     * Renders JavaScript code
     *
     * @param Event $event
     */
    public function renderCode(Event $event)
    {
        /* @var $view View */
        $view = $event->sender;

        echo $view->renderFile(__DIR__ . '/views/google-tag-manager.php', [
            'tagManagerId' => $this->tagManagerId,
        ]);
    }
}
