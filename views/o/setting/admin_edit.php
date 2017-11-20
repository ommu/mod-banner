<?php
/**
 * Banner Settings (banner-setting)
 * @var $this SettingController
 * @var $model BannerSetting
 * version: 1.3.0
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-banner
 * @contact (+62)856-299-4114
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
						'label' => Yii::t('phrase', 'View Banner Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'view',
						),
						'url' => 'Yii::app()->controller->createUrl(\'o/category/view\',array(\'id\'=>$data->primaryKey))'),
					'update' => array(
						'label' => Yii::t('phrase', 'Update Banner Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'update'
						),
						'url' => 'Yii::app()->controller->createUrl(\'o/category/edit\',array(\'id\'=>$data->primaryKey))'),
					'delete' => array(
						'label' => Yii::t('phrase', 'Delete Banner Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'delete'
						),
						'url' => 'Yii::app()->controller->createUrl(\'o/category/delete\',array(\'id\'=>$data->primaryKey))')
				),
				'template' => '{view}|{update}|{delete}',
			));

			$this->widget('application.libraries.core.components.system.OGridView', array(
				'id'=>'banner-category-grid',
				'dataProvider'=>$category->search(),
				'filter'=>$category,
				'afterAjaxUpdate' => 'reinstallDatePicker',
				'columns' => $columnData,
				'pager' => array('header' => ''),
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
		echo Utility::flashError(Yii::app()->user->getFlash('error')); 
	if(Yii::app()->user->hasFlash('success')) 
		echo Utility::flashSuccess(Yii::app()->user->getFlash('success')); 
	?>
	</div>
	<?php //begin.Messages ?>
	
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
