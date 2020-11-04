<?php
/**
 * AdminController
 * @var $this ommu\banner\controllers\AdminController
 * @var $model ommu\banner\models\Banners
 *
 * AdminController implements the CRUD actions for Banners model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 08:14 WIB
 * @modified date 13 February 2019, 05:27 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\banner\models\Banners;
use ommu\banner\models\search\Banners as BannersSearch;
use yii\web\UploadedFile;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
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
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all Banners models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new BannersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($category = Yii::$app->request->get('category')) != null) {
            $category = \ommu\banner\models\BannerCategory::findOne($category);
        }

		$this->view->title = Yii::t('app', 'Banners');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'category' => $category,
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

        if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->banner_filename = UploadedFile::getInstance($model, 'banner_filename');
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Banner success created.'));
				return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
			}
		}

		$this->view->title = Yii::t('app', 'Create Banner');
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

        if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->banner_filename = UploadedFile::getInstance($model, 'banner_filename');
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Banner success updated.'));
				return $this->redirect(['update', 'id'=>$model->banner_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
			}
		}

		$this->subMenu = $this->module->params['banner_submenu'];
		$this->view->title = Yii::t('app', 'Update Banner: {title}', ['title' => $model->title]);
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

		$this->subMenu = $this->module->params['banner_submenu'];
		$this->view->title = Yii::t('app', 'Detail Banner: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
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

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Banner success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
		}
	}

	/**
	 * actionPublish an existing Banners model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Banner success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
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
        if (($model = Banners::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
