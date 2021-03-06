<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\forms\ServiceForm;
use app\models\Logs;
use app\models\Services;
use app\models\Settings;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ServicesController extends BaseController
{

    public $layout = BaseController::LAYOUT_AUTHORIZED;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'deactivate' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Service list
     *
     * @return string
     */
    public function actionIndex()
    {
        $pageSize = Settings::getSettingValue(Settings::SETTING_SHOW_ELEMENTS_ON_PAGE, 15);

        $query = Services::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
        $services = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'pages' => $pages,
            'services' => $services,
        ]);
    }

    /**
     * Add new service
     *
     * @param integer $type
     * @return string|\yii\web\Response
     */
    public function actionAdd($type)
    {
        /** @var \app\models\Users $user */
        $user = Yii::$app->user->identity;
        $model = new ServiceForm();

        if (($type != $model::TYPE_GITHUB && $type != $model::TYPE_BITBUCKET) || !$user->is_admin) {
            Yii::$app->session->setFlash('serviceOperation', [
                'type' => 'alert-danger',
                'icon' => 'mdi mdi-close-circle-o',
                'title' => 'Danger!',
                'message' => 'Что-то пошло не так!',
            ]);

            return $this->redirect(['index']);
        }

        $model->type = $type;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('serviceOperation', [
                'type' => 'alert-success',
                'icon' => 'mdi mdi-check',
                'title' => 'Success!',
                'message' => 'Service added!',
            ]);

            return $this->redirect(['index']);
        }

        return $this->render('add', ['model' => $model]);
    }

    /**
     * Activate service
     *
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionActivate($id)
    {
        /** @var \app\models\Users $user */
        $user = Yii::$app->user->identity;

        if ($user->is_admin) {
            Services::setServiceStatus($id, true);
        }

        return $this->redirect(['index']);
    }

    /**
     * Deactivate service
     *
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDeactivate($id)
    {
        /** @var \app\models\Users $user */
        $user = Yii::$app->user->identity;

        if ($user->is_admin) {
            Services::setServiceStatus($id, false);
        }

        return $this->redirect(['index']);
    }

    /**
     * Change service settings
     *
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionSettings($id)
    {
        /** @var \app\models\Users $user */
        $user = Yii::$app->user->identity;
        $service = Services::findOne(intval($id));

        if (!$service || !$user->is_admin) {
            Yii::$app->session->setFlash('serviceOperation', [
                'type' => 'alert-danger',
                'icon' => 'mdi mdi-close-circle-o',
                'title' => 'Danger!',
                'message' => 'Что-то пошло не так!',
            ]);

            return $this->redirect(['index']);
        }

        $model = new ServiceForm();
        $model->attributes = $service->attributes;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service->attributes = $model->attributes;
            $service->update();

            Logs::setLog(102, [':service_name' => $service->username, ':service_type' => $service->getServiceName()]);

            Yii::$app->session->setFlash('serviceOperation', [
                'type' => 'alert-success',
                'icon' => 'mdi mdi-check',
                'title' => 'Success!',
                'message' => 'Service updated!',
            ]);

            return $this->redirect(['index']);
        }

        return $this->render('settings', ['model' => $model]);
    }

    /**
     * Delete service
     *
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var \app\models\Users $user */
        $user = Yii::$app->user->identity;
        $service = Services::findOne(intval($id));

        if ($service && $user->is_admin && $service->delete()) {
            Logs::setLog(105, [':service_name' => $service->username, ':service_type' => $service->getServiceName()]);

            Yii::$app->session->setFlash('serviceOperation', [
                'type' => 'alert-success',
                'icon' => 'mdi mdi-check',
                'title' => 'Success!',
                'message' => 'Service removed!',
            ]);
        } else {
            Yii::$app->session->setFlash('serviceOperation', [
                'type' => 'alert-danger',
                'icon' => 'mdi mdi-close-circle-o',
                'title' => 'Danger!',
                'message' => 'У вас недостаточно прав для выполнения данного действия!',
            ]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Test connection to service
     *
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionTest($id)
    {
        Services::testApiConnection($id);

        return $this->redirect(['index']);
    }

}