<?php
/**
 * ClickDetailController
 * @var $this app\components\View
 * @var $model ommu\banner\models\BannerClickHistory
 *
 * ClickDetailController implements the CRUD actions for BannerClickHistory model.
 * Reference start
 * TOC :
 *	Index
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:29 WIB
 * @modified date 2 May 2018, 11:10 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-banner
 *
 */
 
namespace ommu\banner\controllers\history;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\banner\models\BannerClickHistory;
use ommu\banner\models\search\BannerClickHistory as BannerClickHistorySearch;

class ClickDetailController extends Controller
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
	 * Lists all BannerClickHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new BannerClickHistorySearch();
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

		$this->view->title = Yii::t('app', 'Banner Click Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Displays a single BannerClickHistory model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {click-id}', ['model-class' => 'Banner Click History', 'click-id' => $model->click->banner->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing BannerClickHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Banner click history success deleted.'));
		return $this->redirect(['manage']);
	}

	/**
	 * Finds the BannerClickHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return BannerClickHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = BannerClickHistory::findOne($id)) !== null) 
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
