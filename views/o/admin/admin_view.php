<?php
/**
 * Banners (banners)
 * @var $this AdminController
 * @var $model Banners
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
		$model->title,
	);
?>

<div class="box">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'banner_id',
				'value'=>$model->banner_id,
			),
			array(
				'name'=>'publish',
				'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->banner_id)), $model->publish),
				'type'=>'raw',
			),
			array(
				'name'=>'cat_id',
				'value'=>$model->cat_id ? $model->category->title->message : '-',
			),
			array(
				'name'=>'title',
				'value'=>$model->title ? $model->title : '-',
			),
			array(
				'name'=>'url',
				'value'=>$model->url ? CHtml::link($model->url, $model->url, array('target' => '_blank')) : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'banner_filename',
				'value'=>$model->banner_filename ? CHtml::link($model->banner_filename, Yii::app()->request->baseUrl.'/public/banner/'.$model->banner_filename, array('target' => '_blank')) : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'banner_desc',
				'value'=>$model->banner_desc ? $model->banner_desc : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'published_date',
				'value'=>!in_array($model->published_date, array('0000-00-00','1970-01-01','0002-12-02','-0001-11-30')) ? $this->dateFormat($model->published_date, 'full', false) : '-',
			),
			array(
				'name'=>'expired_date',
				'value'=>!in_array($model->expired_date, array('0000-00-00','1970-01-01','0002-12-02','-0001-11-30')) ? $this->dateFormat($model->expired_date, 'full', false) : '-',
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