<?php
/**
 * SiteController
 * @var $this SiteController
 * @var $model Articles
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	List
 *	Detail
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 23 Juni 2016, 14:46 WIB
 * @link https://github.com/oMMu/Ommu-Articles
 * @contect (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SiteController extends ControllerApi
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','main','list','detail'),
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
		$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionList() 
	{
		if(Yii::app()->request->isPostRequest) {
			$category = trim($_POST['category']);
			
			$criteria=new CDbCriteria;
			$criteria->with = array(
				'category_relation' => array(
					'alias'=>'category_relation',
				),
				'category_relation.view_cat' => array(
					'alias'=>'view',
				),
			);
			$now = new CDbExpression("NOW()");
			if($category != null && $category != '') {
				$criteria->condition = '(t.expired_date >= curdate() OR t.published_date >= curdate()) OR ((t.expired_date = :date OR t.expired_date = :datestr) OR t.published_date >= curdate())';
				$criteria->params = array(
					':date'=>'0000-00-00', 
					':datestr'=>'1970-01-01', 
				);
				$criteria->compare('t.publish', 1);
				$criteria->compare('view.category_name', $category);
				$criteria->order = 't.expired_date DESC';
		
				$model = Banners::model()->findAll($criteria);
			
				if(!empty($model)) {
					foreach($model as $key => $val) {
						$banner_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl;
						$banner_path = 'public/banner';
						$extension = pathinfo($val->media, PATHINFO_EXTENSION);
						if($val->media != '' && in_array($extension, array('bmp','gif','jpg','png')) && file_exists($banner_path.'/'.$val->media)) {
							$banner_image = $banner_url.'/'.$banner_path.'/'.$val->media;
					
							$data[] = array(
								'id'=>$val->banner_id,
								'title'=>$val->title,
								'image'=>$banner_image,
								'url'=>($val->url != '-' && $val->url != '') ? Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->controller->createUrl('site/click', array('id'=>$val->banner_id, 't'=>Utility::getUrlTitle($val->title))) : '-',
							);
						}
					}
				} else
					$data = array();
				
				$this->_sendResponse(200, CJSON::encode($this->renderJson($data)));
				
			} else
				$this->redirect(Yii::app()->createUrl('site/index'));
			
		} else 
			$this->redirect(Yii::app()->createUrl('site/index'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = Articles::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='articles-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
