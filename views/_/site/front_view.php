<?php
/**
 * Banners (banners)
 * @var $this SiteController
 * @var $model Banners
 * version: 1.3.0
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2015 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-banner
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Banners'=>array('manage'),
		$model->title,
	);
?>

<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'banner_id',
		'publish',
		'cat_id',
		'title',
		'url',
		'banner_filename',
		'banner_desc',
		'published_date',
		'expired_date',
		'creation_date',
		'creation_id',
		'modified_date',
		'modified_id',
		'slug',
	),
)); ?>