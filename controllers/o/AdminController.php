<?php
/**
 * AdminController
 * @var $this AdminController
 * @var $model Banners
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	Edit
 *	View
 *	Runaction
 *	Delete
 *	Publish
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 23 January 2018, 07:07 WIB
 * @link https://github.com/ommu/mod-banner
 *
 *----------------------------------------------------------------------------------------------------------
 */

class AdminController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		if(!Yii::app()->user->isGuest) {
			if(in_array(Yii::app()->user->level, array(1,2))) {
				$arrThemes = $this->currentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			}
		} else
			$this->redirect(Yii::app()->createUrl('site/login'));
	}

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','manage','add','edit','view','runaction','delete','publish'),
				'users'=>array('@'),
				'expression'=>'in_array(Yii::app()->user->level, array(1,2))',
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
		$this->redirect(array('manage'));
	}

	/**
	 * Manages all models.
	 */
	public function actionManage($category=null) 
	{
		$model=new Banners('search');
		$model->unsetAttributes();	// clear any default values
		if(Yii::app()->getRequest()->getParam('Banners')) {
			$model->attributes=Yii::app()->getRequest()->getParam('Banners');
		}

		$columns = $model->getGridColumn($this->gridColumnTemp());

		$pageTitle = Yii::t('phrase', 'Banners');
		if($category != null) {
			$data = BannerCategory::model()->findByPk($category);
			$pageTitle = Yii::t('phrase', 'Banners: Category {category_name}', array ('{category_name}'=>$data->title->message));
		}
		
		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage', array(
			'model'=>$model,
			'columns' => $columns,
		));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$setting = BannerSetting::model()->findByPk(1, array(
			'select' => 'banner_file_type',
		));
		$banner_file_type = unserialize($setting->banner_file_type);
		if(empty($banner_file_type))
			$banner_file_type = array();
		
		$model=new Banners;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Banners'])) {
			$model->attributes=$_POST['Banners'];

			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'Banner success created.'));
				$this->redirect(array('manage'));
			}
		}

		$this->pageTitle = Yii::t('phrase', 'Create Banner');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_add', array(
			'model'=>$model,
			'banner_file_type'=>$banner_file_type,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id) 
	{
		$setting = BannerSetting::model()->findByPk(1, array(
			'select' => 'banner_file_type',
		));
		$banner_file_type = unserialize($setting->banner_file_type);
		if(empty($banner_file_type))
			$banner_file_type = array();
		
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Banners'])) {
			$model->attributes=$_POST['Banners'];

			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'Banner success updated.'));
				$this->redirect(array('manage'));
			}
		}

		$this->pageTitle = Yii::t('phrase', 'Update Banner: {title}', array('{title}'=>$model->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit', array(
			'model'=>$model,
			'banner_file_type'=>$banner_file_type,
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);

		$this->pageTitle = Yii::t('phrase', 'Detail Banner: {title}', array('{title}'=>$model->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_view', array(
			'model'=>$model,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRunaction() {
		$id       = $_POST['trash_id'];
		$criteria = null;
		$actions  = Yii::app()->getRequest()->getParam('action');

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('banner_id', $id);

			if($actions == 'publish') {
				Banners::model()->updateAll(array(
					'publish' => 1,
				),$criteria);
			} elseif($actions == 'unpublish') {
				Banners::model()->updateAll(array(
					'publish' => 0,
				),$criteria);
			} elseif($actions == 'trash') {
				Banners::model()->updateAll(array(
					'publish' => 2,
				),$criteria);
			} elseif($actions == 'delete') {
				Banners::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!Yii::app()->getRequest()->getParam('ajax')) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) 
	{
		$model=$this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model->publish = 2;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			
			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-banners',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Banner success deleted.').'</strong></div>',
				));
				/*
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'Banner success deleted.'));
				$this->redirect(array('manage'));
				*/
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Delete Banner: {title}', array('{title}'=>$model->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionPublish($id) 
	{
		$model=$this->loadModel($id);
		
		$title = $model->publish == 1 ? Yii::t('phrase', 'Unpublish') : Yii::t('phrase', 'Publish');
		$replace = $model->publish == 1 ? 0 : 1;

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			//change value active or publish
			$model->publish = $replace;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-banners',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Banner success updated.').'</strong></div>',
				));
				/*
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'Banner success updated.'));
				$this->redirect(array('manage'));
				*/
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', '{title} Banner: {title}', array('{title}'=>$title, '{title}'=>$model->title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_publish', array(
			'title'=>$title,
			'model'=>$model,
		));
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
