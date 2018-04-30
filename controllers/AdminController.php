<?php
/**
 * AdminController
 * @var $this yii\web\View
 * @var $model app\modules\banner\models\Banners
 * version: 0.0.1
 *
 * AdminController implements the CRUD actions for Banners model.
 * Reference start
 * TOC :
 *  Index
 *  Create
 *  Update
 *  View
 *  Delete
 *  RunAction
 *  Publish
 *
 *  findModel
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 08:14 WIB
 * @contact (+62)857-4115-5177
 *
 */
 
namespace app\modules\banner\controllers;

use Yii;
use app\modules\banner\models\Banners;
use app\modules\banner\models\search\Banners as BannersSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use mdm\admin\components\AccessControl;

class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'publish' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Banners models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BannersSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if($gridColumn != null && count($gridColumn) > 0) {
            foreach($gridColumn as $key => $val) {
                if($gridColumn[$key] == 1)
                    $cols[] = $key;
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        $this->view->title = Yii::t('app', 'Banners');
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns'     => $columns,
        ]);
    }

    /**
     * Creates a new Banners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banners();
        $model->scenario = 'formCreate';

        if(Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->banner_filename = UploadedFile::getInstance($model, 'banner_filename');

            if($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Banner success created.'));
                return $this->redirect(['index']);
            }
        }

        $this->view->title = Yii::t('app', 'Create Banners');
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Banners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if(Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->banner_filename = UploadedFile::getInstance($model, 'banner_filename');
            if(!($model->banner_filename instanceof UploadedFile)) {
                $model->banner_filename = $model->old_banner_filename_i;
            }

            if($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Banner success updated.'));
                return $this->redirect(['index']);
            }
        }

        $this->view->title = Yii::t('app', 'Update {modelClass}: {title}', ['modelClass' => 'Banners', 'title' => $model->title]);
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_update', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Banners model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $this->view->title = Yii::t('app', 'View {modelClass}: {title}', ['modelClass' => 'Banners', 'title' => $model->title]);
        $this->view->description = '';
        $this->view->keywords = '';
        return $this->render('admin_view', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Banners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->publish = 2;

        if ($model->save(false, ['publish'])) {
            //return $this->redirect(['view', 'id' => $model->banner_id]);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Banners success deleted.'));
            return $this->redirect(['index']);
        }
    }

    /**
     * Publish/Unpublish an existing Banners model.
     * If publish/unpublish is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPublish($id)
    {
        $model = $this->findModel($id);
        $replace = $model->publish == 1 ? 0 : 1;
        $model->publish = $replace;

        if ($model->save(false, ['publish'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Banners success updated.'));
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Banners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Banners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banners::findOne($id)) !== null) 
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
