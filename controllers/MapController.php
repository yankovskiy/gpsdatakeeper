<?php

namespace app\controllers;

use app\models\GpsData;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * MapController is the controller for interaction with map
 * @package app\controllers
 */
class MapController extends \yii\web\Controller
{
    public $layout = 'map';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'save-data' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays saved gps-data by token.
     * If token not found function run redirect to home page.
     * @param string $token token for saved gps-data
     * @return Response | string
     */
    public function actionView($token)
    {
        $gpsData = GpsData::getDataByToken($token);
        if ($gpsData == null) {
            return $this->goHome();
        }

        $isGuest = Yii::$app->user->isGuest;
        $isOwner = false;
        if (!$isGuest) {
            $isOwner = $gpsData->user_id == Yii::$app->user->id;
        }

        return $this->render('view', [
            'gpsData' => $gpsData,
            'isGuest' => $isGuest,
            'isOwner' => $isOwner,
        ]);
    }

    /**
     * Saves GPS-data to the database
     * @return array
     * @throws BadRequestHttpException if request is not ajax or input data is incorrect
     */
    public function actionSaveData()
    {
        if (!Yii::$app->request->isAjax ||
            Yii::$app->request->post('title') == null ||
            Yii::$app->request->post('data') == null ||
            Yii::$app->request->post('center') == null ||
            Yii::$app->request->post('zoom') == null
        ) {
            throw new BadRequestHttpException();
        }

        $gpsData = null;
        $isOwner = false;

        // edit
        if (!Yii::$app->user->isGuest && Yii::$app->request->post('token') != null) {
            $gpsData = GpsData::getDataByToken(Yii::$app->request->post('token'), true);
            $isOwner = isset($gpsData);
        // create
        } else {
            $isOwner = !Yii::$app->user->isGuest;
        }

        // if we not in edit mode
        if (!isset($gpsData)) {
            $gpsData = new GpsData();
        }

        $data = Yii::$app->request->post('data');
        $center = Json::encode(
            Yii::$app->request->post('center'),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK
        );
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$gpsData->saveData(
            $data,
            Yii::$app->request->post('title'),
            Yii::$app->request->post('zoom'),
            $center
        )) {
            return ['status' => 'error'];
        } else {
            return [
                'status' => 'success',
                'url' => Url::to(['view', 'token' => $gpsData->token]),
                'isOwner' => $isOwner,
                'token' => $gpsData->token,
                'isGuest' => Yii::$app->user->isGuest,
            ];
        }

    }

}
