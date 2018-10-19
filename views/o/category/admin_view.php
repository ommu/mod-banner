<?php
/**
 * Banner Categories (banner-category)
 * @var $this CategoryController
 * @var $model BannerCategory
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
		$model->name,
	);
?>

<div class="dialog-content">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'cat_id',
				'value'=>$model->cat_id,
			),
			array(
				'name'=>'publish',
				'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->cat_id)), $model->publish),
				'type'=>'raw',
			),
			array(
				'name'=>'name',
				'value'=>$model->name ? $model->title->message : '-',
			),
			array(
				'name'=>'desc',
				'value'=>$model->desc ? $model->description->message : '-',
			),
			array(
				'name'=>'cat_code',
				'value'=>$model->cat_code ? $model->cat_code : '-',
			),
			array(
				'name'=>'banner_size',
				'value'=>BannerCategory::getPreviewSize($model->banner_size),
				'type'=>'raw',
			),
			array(
				'name'=>'banner_limit',
				'value'=>$model->banner_limit ? $model->banner_limit : '-',
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->creation_date) : '-',
			),
			array(
				'name'=>'creation_search',
				'value'=>$model->creation->displayname ? $model->creation->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
			),
			array(
				'name'=>'modified_search',
				'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
			),
			array(
				'name'=>'slug',
				'value'=>$model->slug ? $model->slug : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>