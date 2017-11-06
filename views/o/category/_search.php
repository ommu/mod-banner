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
 * @link https://github.com/ommu/mod-banner
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('name'); ?>
			<?php echo $form->textField($model,'name'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('desc'); ?>
			<?php echo $form->textField($model,'desc'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('cat_code'); ?>
			<?php echo $form->textField($model,'cat_code'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('banner_size'); ?>
			<?php echo $form->textField($model,'banner_size'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?>
			<?php //echo $form->textField($model,'creation_date');
			$this->widget('application.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'creation_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'dd-mm-yy',
				),
				'htmlOptions'=>array(
					'class' => 'span-4',
				 ),
			));; ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_search'); ?>
			<?php echo $form->textField($model,'creation_search'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?>
			<?php //echo $form->textField($model,'modified_date');
			$this->widget('application.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'modified_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'dd-mm-yy',
				),
				'htmlOptions'=>array(
					'class' => 'span-4',
				 ),
			));; ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_search'); ?>
			<?php echo $form->textField($model,'modified_search'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('slug'); ?>
			<?php echo $form->textField($model,'slug'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?>
			<?php echo $form->dropDownList($model,'publish', array('0'=>Yii::t('phrase', 'No'), '1'=>Yii::t('phrase', 'Yes'))); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('banner_limit'); ?>
			<?php echo $form->textField($model,'banner_limit'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
