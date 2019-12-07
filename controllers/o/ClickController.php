<?php
/**
 * ClickController
 * @var $this ommu\banner\controllers\o\ClickController
 * @var $model ommu\banner\models\BannerClicks
 *
 * ClickController implements the CRUD actions for BannerClicks model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:06 WIB
 * @modified date 24 January 2019, 17:53 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\controllers\o;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\banner\models\BannerClicks;
use ommu\banner\models\search\BannerClicks as BannerClicksSearch;

class ClickController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if(Yii::$app->request->get('id') || Yii::$app->request->get('banner'))
			$this->subMenu = $this->module->params['banner_submenu'];
	}

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
	 * Lists all BannerClicks models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new BannerClicksSearch();
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

		if(($banner = Yii::$app->request->get('banner')) != null) {
			$this->subMenuParam = $banner;
			$banner = \ommu\banner\models\Banners::findOne($banner);
		}
		if(($user = Yii::$app->request->get('user')) != null)
			$user = \ommu\users\models\Users::findOne($user);

		$this->view->title = Yii::t('app', 'Clicks');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'banner' => $banner,
			'user' => $user,
		]);
	}

	/**
	 * Displays a single BannerClicks model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->subMenuParam = $model->banner_id;
		$this->view->title = Yii::t('app', 'Detail Click: {banner-id}', ['banner-id' => $model->banner->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing BannerClicks model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Banner click success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'banner'=>$model->banner_id]);
	}

	/**
	 * Finds the BannerClicks model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return BannerClicks the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = BannerClicks::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
