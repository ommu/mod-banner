<?php
/**
 * SiteController
 * @var $this SiteController
 * @var $model Articles
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	List
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 23 Juni 2016, 14:46 WIB
 * @link https://github.com/ommu/mod-banner
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
	 * Initialize public template
	 */
	public function init() 
	{
		$arrThemes = Utility::getCurrentTemplate('public');
		Yii::app()->theme = $arrThemes['folder'];
		$this->layout = $arrThemes['layout'];
		//$this->pageGuest = true;
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

			$setting = BannerSetting::model()->findByPk(1, array(
				'select' => 'banner_file_type',
			));
			$banner_file_type = unserialize($setting->banner_file_type);
			if(empty($banner_file_type))
				$banner_file_type = array();
			
			$categoryFind = BannerCategory::model()->findByAttributes(array('cat_code' => $category), array(
				'select' => 'banner_limit',
			));
			
			$criteria=new CDbCriteria;
			$criteria->with = array(
				'category' => array(
					'alias'=>'category',
				),
			);
			if($category) {
				$criteria->condition = '(t.expired_date >= curdate() OR t.published_date >= curdate()) OR ((t.expired_date = :date OR t.expired_date = :datestr) OR t.published_date >= curdate())';
				$criteria->params = array(
					':date'=>'0000-00-00', 
					':datestr'=>'1970-01-01', 
				);
				$criteria->compare('t.publish', 1);
				$criteria->compare('category.cat_code', $category);
				$criteria->order = 't.expired_date ASC';
				$criteria->limit = $categoryFind->banner_limit;
				$criteria->order = 't.expired_date DESC';
		
				$model = Banners::model()->findAll($criteria);
			
				if(!empty($model)) {
					foreach($model as $key => $val) {
						$banner_url = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl;
						$banner_path = 'public/banner';
						$extension = pathinfo($val->banner_filename, PATHINFO_EXTENSION);
						if($val->banner_filename && in_array($extension, $banner_file_type) && file_exists($banner_path.'/'.$val->banner_filename)) {
							$banner_url_path = $banner_url.'/'.$banner_path.'/'.$val->banner_filename;
					
							$data[] = array(
								'id'=>$val->banner_id,
								'title'=>$val->title,
								'image'=>$banner_url_path,
								'url'=>($val->url && $val->url != '-') ? Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->controller->createUrl('site/click', array('id'=>$val->banner_id, 'slug'=>$this->urlTitle($val->title))) : '-',
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
