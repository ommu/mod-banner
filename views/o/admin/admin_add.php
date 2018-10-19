<?php
/**
 * Banners (banners)
 * @var $this AdminController
 * @var $model Banners
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
		'Banners'=>array('manage'),
		Yii::t('phrase', 'Create'),
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'banner_file_type'=>$banner_file_type,
	)); ?>
</div>
