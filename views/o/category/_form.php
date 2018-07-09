<?php
/**
 * Banner Categories (banner-category)
 * @var $this CategoryController
 * @var $model BannerCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 23 January 2018, 07:07 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'banner-category-form',
	'enableAjaxValidation'=>true,
	/*
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
	),
	*/
)); ?>

<div class="dialog-content">
	<fieldset>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'name_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model, 'name_i', array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'name_i'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'desc_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model, 'desc_i', array('rows'=>6, 'cols'=>50, 'maxlength'=>128, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'desc_i'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'banner_limit', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model, 'banner_limit', array('maxlength'=>2, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'banner_limit'); ?>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('banner_size');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				if(!$model->getErrors())
					$model->banner_size = unserialize($model->banner_size);
				echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'banner_size[width]', array('maxlength'=>4,'class'=>'form-control')); ?>&nbsp;&nbsp;&nbsp;
				<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'banner_size[height]', array('maxlength'=>4,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'banner_size'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'publish', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model, 'publish', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'publish'); ?>
				<?php echo $form->error($model, 'publish'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>