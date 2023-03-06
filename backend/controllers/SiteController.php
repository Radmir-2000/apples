<?php

namespace backend\controllers;

use Yii;
use common\models\LoginForm;
use backend\models\Apples;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;

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
                        'actions' => ['logout', 'index', 'generate', 'fall'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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

    public function getApple($id)
    {
        if (($model = Apples::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Ошибка при указании яблока');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $apples = Apples::find()->all();

        if ($post = Yii::$app->request->post()) {
            if (empty($post['id']) || empty($post['percent']) || !is_numeric($post['percent'])) {
                Yii::$app->session->setFlash('error', 'Необходимо указать число');
            } else {
                if ($apple = $this->getApple((int)$post['id'])) {
                    if ($error = $apple->eat((int)$post['percent'])) {
                        Yii::$app->session->setFlash('error', $error);
                    } else {
                        if ($apple->rest > 0) {
                            Yii::$app->session->setFlash('success', 'Яблоко успешно откушено');
                        } else {
                            Yii::$app->session->setFlash('success', 'Яблоко успешно съедено');
                        }
                    }

                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка определения яблока');
                }
            }
        }

        return $this->render('index', [
            'apples' => $apples,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionFall()
    {
        if ($get = Yii::$app->request->get()) {
            if (empty($get['id'])) {
                Yii::$app->session->setFlash('error', 'Ошибка определения яблока');
            } else {
                if ($apple = $this->getApple((int)$get['id'])) {
                    if ($error = $apple->fall()) {
                        Yii::$app->session->setFlash('error', $error);
                    } else {
                        Yii::$app->session->setFlash('success', 'Яблоко сорвано');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка определения яблока');
                }
            }
        }

        return $this->redirect(['index']);
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
