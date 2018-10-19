<?php
/**
 * Banner Categories (banner-category)
 * @var $this CategoryController
 * @var $model BannerCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 23 January 2018, 07:07 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

	$this->breadcrumbs=array(
		'Banner Categories'=>array('manage'),
		$model->name=>array('view','id'=>$model->cat_id),
		Yii::t('phrase', 'Update'),
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>