<?php
/**
 * SettingController
 * @var $this yii\web\View
 * @var $model ommu\banner\models\BannerSetting
 *
 * SettingController implements the CRUD actions for BannerSetting model.
 * Reference start
 * TOC :
 *	Index
 *	Update
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 6 October 2017, 06:22 WIB
 * @modified date 30 April 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */
 
namespace ommu\banner\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\banner\models\BannerSetting;
use ommu\banner\models\search\BannerCategory as BannerCategorySearch;

class SettingController extends Controller
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
				],
			],
		];
	}

	/**
	 * Lists all BannerSetting models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->redirect(['update']);
	}

	/**
	 * Updates an existing BannerSetting model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate()
	{
		$this->layout = 'admin_default';
		
		$searchModel = new BannerCategorySearch();
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

		$model = BannerSetting::findOne(1);
		if ($model === null) 
			$model = new BannerSetting();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Banner setting success updated.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id' => $model->id]);
			}
		}

		$this->view->title = Yii::t('app', 'Banner Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing BannerSetting model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Banner setting success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * Finds the BannerSetting model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return BannerSetting the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = BannerSetting::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}