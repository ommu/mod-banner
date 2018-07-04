<?php
/**
 * SiteController
 * @var $this SiteController
 * @var $model Banners
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	View
 *	Click
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-banner
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SiteController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';
	
	public $permission;

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		$this->permission = 0;
		$permission = BannerSetting::getInfo('permission');
		if($permission == 1 || ($permission == 0 && !Yii::app()->user->isGuest))
			$this->permission = 1;
			
		$arrThemes = Utility::getCurrentTemplate('public');
		Yii::app()->theme = $arrThemes['folder'];
		$this->layout = $arrThemes['layout'];
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','click'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$siteType = OmmuSettings::getInfo('site_type');		
		if($this->permission == 0)
			$this->redirect($siteType == 0 ? Yii::app()->createUrl('site/index') : Yii::app()->createUrl('site/login'));
		
		$setting = BannerSetting::model()->findByPk(1, array(
			'select' => 'meta_description, meta_keyword',
		));

		$criteria=new CDbCriteria;
		$criteria->condition = 'publish = :publish';
		$criteria->params = array(':publish'=>1);
		$criteria->order = 'creation_date DESC';

		$dataProvider = new CActiveDataProvider('Banners', array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>10,
			),
		));

		$this->pageTitle =  Yii::t('phrase', 'Banners');
		$this->pageDescription = $setting->meta_description;
		$this->pageMeta = $setting->meta_keyword;
		$this->render('front_index', array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$siteType = OmmuSettings::getInfo('site_type');
		if($this->permission == 0)
			$this->redirect($siteType == 0 ? Yii::app()->createUrl('site/index') : Yii::app()->createUrl('site/login'));
		
		$setting = BannerSetting::model()->findByPk(1, array(
			'select' => 'meta_keyword',
		));

		$model=$this->loadModel($id);
		BannerViews::insertView($model->banner_id);

		$this->pageTitle =  Yii::t('phrase', 'Detail Banners');
		$this->pageDescription = $model->banner_desc;
		$this->pageMeta = $setting->meta_keyword;
		$this->render('front_view', array(
			'model'=>$model,
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionClick($id) 
	{
		$model=$this->loadModel($id);
		if($id == null || $model == null)
			$this->redirect(Yii::app()->createUrl('site/index'));
		else {
			BannerClicks::insertClick($model->banner_id);
			$this->redirect($model->url);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = Banners::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='banners-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
