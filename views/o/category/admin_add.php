<?php
/**
 * Banner Categories (banner-category)
 * @var $this CategoryController
 * @var $model BannerCategory
 * @var $form CActiveForm
 * version: 1.3.0
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-banner
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Banner Categories'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>