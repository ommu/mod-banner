<?php
/**
 * Banners (banners)
 * @var $this AdminController
 * @var $model Banners
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @modified date 23 January 2018, 07:07 WIB
 * @link https://github.com/ommu/ommu-banner
 *
 */

	$this->breadcrumbs=array(
		'Banners'=>array('manage'),
		$model->title,
	);
?>

<div class="box">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'banner_id',
				'value'=>$model->banner_id,
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
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
				'value'=>!in_array($model->published_date, array('0000-00-00','1970-01-01')) ? Utility::dateFormat($model->published_date) : '-',
			),
			array(
				'name'=>'expired_date',
				'value'=>!in_array($model->expired_date, array('0000-00-00','1970-01-01')) ? Utility::dateFormat($model->expired_date) : '-',
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
			),
			array(
				'name'=>'creation_id',
				'value'=>$model->creation_id ? $model->creation->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified_id ? $model->modified->displayname : '-',
			),
			array(
				'name'=>'slug',
				'value'=>$model->slug ? $model->slug : '-',
			),
		),
	)); ?>
</div>