<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use common\models\LoginForm;
use backend\models\Apples;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'respass'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'generate'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $apples = Apples::find()->all();

        return $this->render('index', [
            'apples' => $apples,
        ]);
    }

    /**
     * Generate apples
     *
     * @return string
     */
    public function actionGenerate()
    {
        Apples::deleteAll();

        $count = mt_rand(4, 12);
        for($i = 1; $i <= $count; $i++) {
            Apples::create();
        }

        return $this->redirect(['index']);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

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

/*
    public function actionRespass()
    {
        $user = User::findIdentity(1);
        $user->setPassword('1122334455');
        $user->save();

        return $this->redirect(['site/index']);
    }
*/
}
