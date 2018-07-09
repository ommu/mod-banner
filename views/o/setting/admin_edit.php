<?php
/**
 * Banner Settings (banner-setting)
 * @var $this SettingController
 * @var $model BannerSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 23 January 2018, 07:08 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

	$this->breadcrumbs=array(
		'Banner Settings',
	);
?>

<div id="partial-banner-category">
	<div class="boxed">
		<?php //begin.Grid Item ?>
		<?php 
			$columnData   = $columns;
			array_push($columnData, array(
				'header' => Yii::t('phrase', 'Options'),
				'class'=>'CButtonColumn',
				'buttons' => array(
					'view' => array(
						'label' => Yii::t('phrase', 'Detail Banner Category'),
						'imageUrl' => Yii::app()->params['grid-view']['buttonImageUrl'],
						'options' => array(
							'class' => 'view',
						),
						'url' => 'Yii::app()->controller->createUrl(\'o/category/view\', array(\'id\'=>$data->primaryKey))'),
					'update' => array(
						'label' => Yii::t('phrase', 'Update Banner Category'),
						'imageUrl' => Yii::app()->params['grid-view']['buttonImageUrl'],
						'options' => array(
							'class' => 'update',
						),
						'url' => 'Yii::app()->controller->createUrl(\'o/category/edit\', array(\'id\'=>$data->primaryKey))'),
					'delete' => array(
						'label' => Yii::t('phrase', 'Delete Banner Category'),
						'imageUrl' => Yii::app()->params['grid-view']['buttonImageUrl'],
						'options' => array(
							'class' => 'delete',
						),
						'url' => 'Yii::app()->controller->createUrl(\'o/category/delete\', array(\'id\'=>$data->primaryKey))')
				),
				'template' => '{view}|{update}|{delete}',
			));

			$this->widget('application.libraries.yii-traits.system.OGridView', array(
				'id'=>'banner-category-grid',
				'dataProvider'=>$category->search(),
				'filter'=>$category,
				'columns'=>$columnData,
				'template'=>Yii::app()->params['grid-view']['gridTemplate'],
				'pager'=>array('header'=>''),
				'afterAjaxUpdate'=>'reinstallDatePicker',
			));
		?>
		<?php //end.Grid Item ?>
	</div>
</div>

<div class="form" name="post-on">
	<?php //begin.Messages ?>
	<div id="ajax-message">
	<?php 
	if(Yii::app()->user->hasFlash('error')) 
		echo $this->flashMessage(Yii::app()->user->getFlash('error'), 'error'); 
	if(Yii::app()->user->hasFlash('success')) 
		echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success'); 
	?>
	</div>
	<?php //begin.Messages ?>
	
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
