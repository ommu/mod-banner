<?php
/**
 * SettingController
 * @var $this yii\web\View
 * @var $model app\modules\banner\models\BannerSetting
 * version: 0.0.1
 *
 * SettingController implements the CRUD actions for BannerSetting model.
 * Reference start
 * TOC :
 *	Index
 *	Update
 *
 *	findModel
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 6 October 2017, 06:22 WIB
 * @contact (+62)856-299-4114
 *
 */
 
namespace app\modules\banner\controllers;

use Yii;
use app\modules\banner\models\BannerSetting;
use app\modules\banner\models\search\BannerCategory as BannerCategorySearch;
use yii\data\ActiveDataProvider;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use mdm\admin\components\AccessControl;

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

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Banner Setting success updated.'));
			return $this->redirect(['update']);

		} else {
			$this->view->title = Yii::t('app', 'Banner Settings');
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_update', [
				'model' => $model,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'columns'	  => $columns,
			]);
		}
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
		if (($model = BannerSetting::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
