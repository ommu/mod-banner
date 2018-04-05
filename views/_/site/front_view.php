<?php
/**
 * Banners (banners)
 * @var $this SiteController
 * @var $model Banners
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-banner
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