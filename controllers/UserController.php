<?php

namespace app\controllers;

use app\components\AuthHandler;
use app\models\Auth;
use app\models\ChangePasswordForm;
use app\models\DownloadGspsDataForm;
use app\models\GpsData;
use app\models\GpsDataSearch;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * UserController is the controller for user actions (login, register, reset password, etc...)
 * @package app\controllers
 */
class UserController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Called when user successfully authenticated via external service
     * @param yii\authclient\ClientInterface $client
     */
    public function onAuthSuccess($client)
    {
        $url = (new AuthHandler($client))->handle();
        if ($url !== null) {
            $this->redirect($url);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['signup', 'request-password-reset', 'login', 'logout', 'index', 'profile', 'delete-gps-data'],
                'rules' => [
                    [
                        'actions' => ['signup', 'request-password-reset', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'profile', 'delete-gps-data', 'unlink'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete-gps-data' => ['post'],
                    'unlink' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string|array
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        $model->password = '';
        $this->layout = 'backend-empty';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     * After user sign up successfully run autologin and redirect to home page
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        $this->layout = 'backend-empty';
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your mail for further instructions');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address');
            }
        }

        $this->layout = 'backend-empty';
        return $this->render('request-password-reset', [
            'model' => $model,
        ]);
    }

    /**
     * Shows dialog for download gps data
     * @param string $token data token
     */
    public function actionDownloadGpsData($token)
    {
        // todo отображение диалога для скачивания данных
        if (Yii::$app->request->isAjax) {
            $model = new DownloadGspsDataForm();
            $data = GpsData::getDataByToken($token, true);
            if (isset($data)) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['data' => $data->data, 'fileName' => $model->fileName, 'format' => mb_strtolower($model->format)];
                }

                $model->fileName = $data->title;
                $model->format = 'GPX';

                return $this->renderAjax('download-gps-data', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'backend-empty';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Password has been changed. Now you can sign in');
            return $this->redirect('login');
        }


        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    /**
     * Display user profile main page
     */
    public function actionIndex()
    {
        return $this->renderGpsDataTable();
    }

    /**
     * Deletes gps data by token
     * Function remove data only if user is owner
     * @param string $token
     */
    public function actionDeleteGpsData($token)
    {
        $data = GpsData::getDataByToken($token, true);
        if (isset($data)) {
            $data->delete();
        }

        $this->renderGpsDataTable();
    }

    /**
     * Renders table contains GPS data for current user
     * @return string
     */
    private function renderGpsDataTable()
    {
        $model = new GpsDataSearch();
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('index', [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays user profile for change password
     */
    public function actionProfile()
    {
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changePassword()) {
            Yii::$app->session->setFlash('success', 'Password has been changed');
        }

        $model->password = '';
        $googleAuth = Auth::findOne(['user_id' => Yii::$app->user->id, 'source' => 'google']);

        return $this->render('profile', ['model' => $model, 'googleAuth' => $googleAuth]);
    }

    /**
     * Removes link to social network account
     * After remove or fail redirect to profile and display status message
     * @param $id int id link for remove
     */
    public function actionUnlink($id)
    {
        $auth = Auth::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);

        if (isset($auth)) {
            $auth->delete();
            Yii::$app->session->setFlash('success', 'Unlink account successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Link could not be found');
        }

        $this->redirect('profile');
    }
}
