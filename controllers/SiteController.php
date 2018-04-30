<?php
/**
 * SiteController
 * @var $this yii\web\View
 * @var $model app\modules\banner\models\Banners
 * version: 0.0.1
 *
 * SiteController implements the CRUD actions for Banners model.
 * Reference start
 * TOC :
 *	Index
 *	Click
 *
 *	findModel
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 21 October 2017, 18:53 WIB
 * @contact (+62)856-299-4114
 *
 */
 
namespace app\modules\banner\controllers;

use Yii;
use app\modules\banner\models\Banners;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use mdm\admin\components\AccessControl;

class SiteController extends Controller
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
	 * Lists all Banners models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->redirect(['/site/index']);
	}

	/**
	 * @param integer $id
	 * @return mixed
	 */
	public function actionClick($id)
	{
		$model = $this->findModel($id);

		if($model->view->publish == 0)
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

		if(BannerClicks::insertCLick($model->banner_id)) {
			$redirectUrl = $model->banner->url;
			return $this->redirect([$redirectUrl]);

		} else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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
