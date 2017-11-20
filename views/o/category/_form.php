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

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'banner-category-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">
	<fieldset>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'name_i'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'name_i',array('maxlength'=>32,'class'=>'span-8')); ?>
				<?php echo $form->error($model,'name_i'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'desc_i'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'desc_i',array('maxlength'=>64,'class'=>'span-11 smaller')); ?>
				<?php echo $form->error($model,'desc_i'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'banner_limit'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'banner_limit',array('class'=>'span-3', 'maxlength'=>2)); ?>
				<?php echo $form->error($model,'banner_limit'); ?>
			</div>
		</div>
		
		<div class="clearfix">
			<label><?php echo $model->getAttributeLabel('banner_size');?> <span class="required">*</span></label>
			<div class="desc">
				<?php 
				if(!$model->getErrors())
					$model->banner_size = unserialize($model->banner_size);
				echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'banner_size[width]',array('maxlength'=>4,'class'=>'span-3')); ?>&nbsp;&nbsp;&nbsp;
				<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'banner_size[height]',array('maxlength'=>4,'class'=>'span-3')); ?>
				<?php echo $form->error($model,'banner_size'); ?>
			</div>
		</div>

		<div class="clearfix publish">
			<?php echo $form->labelEx($model,'publish'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'publish'); ?>
				<?php echo $form->labelEx($model,'publish'); ?>
				<?php echo $form->error($model,'publish'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>