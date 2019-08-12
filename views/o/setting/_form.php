<?php
/**
 * Banner Settings (banner-setting)
 * @var $this SettingController
 * @var $model BannerSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 23 January 2018, 07:08 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */
	
	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('input[name="BannerSetting[banner_validation]"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('input[name="BannerSetting[banner_resize]"][value="0"]').prop('checked', true);
		}
	});
EOP;
	$cs->registerScript('validation', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'banner-setting-form',
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

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<h3><?php echo Yii::t('phrase', 'Public Settings');?></h3>
<fieldset>

	<div class="form-group row">
		<label class="col-form-label col-lg-3 col-md-3 col-sm-12">
			<?php echo $model->getAttributeLabel('license');?> <span class="required">*</span><br/>
			<span><?php echo Yii::t('phrase', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.');?></span>
		</label>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php if($model->isNewRecord || (!$model->isNewRecord && $model->license == '')) {
				$model->license = $this->licenseCode();
				echo $form->textField($model, 'license', array('maxlength'=>32, 'class'=>'form-control'));
			} else
				echo $form->textField($model, 'license', array('maxlength'=>32, 'class'=>'form-control', 'disabled'=>'disabled'));?>
			<?php echo $form->error($model, 'license'); ?>
			<div class="small-px"><?php echo Yii::t('phrase', 'Format: XXXX-XXXX-XXXX-XXXX');?></div>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'permission', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<div class="small-px"><?php echo Yii::t('phrase', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.');?></div>
			<?php echo $form->radioButtonList($model, 'permission', array(
				1 => Yii::t('phrase', 'Yes, the public can view banner unless they are made private.'),
				0 => Yii::t('phrase', 'No, the public cannot view banner.'),
			), array('class'=>'form-control')); ?>
			<?php echo $form->error($model, 'permission'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'meta_description', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->textArea($model, 'meta_description', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
			<?php echo $form->error($model, 'meta_description'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'meta_keyword', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->textArea($model, 'meta_keyword', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
			<?php echo $form->error($model, 'meta_keyword'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'banner_validation', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->radioButtonList($model, 'banner_validation', array(
				1 => 'Yes, validation banner size before upload.',
				0 => 'No, not validation banner size before upload.',
			), array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'banner_validation', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'banner_resize', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->radioButtonList($model, 'banner_resize', array(
				1 => 'Yes, resize banner after upload.',
				0 => 'No, not resize banner after upload.',
			), array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'banner_resize'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'banner_file_type', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php
			if(!$model->getErrors()) {
				$banner_file_type = unserialize($model->banner_file_type);
				if(!empty($banner_file_type))
					$model->banner_file_type = Utility::formatFileType($banner_file_type, false);
			}
			echo $form->textField($model, 'banner_file_type', array('class'=>'form-control')); ?>
			<?php echo $form->error($model, 'banner_file_type'); ?>
			<div class="small-px">pisahkan jenis file dengan koma (,). example: "jpg, png, bmp"</div>
		</div>
	</div>

	<div class="form-group row submit">
		<label class="col-form-label col-lg-3 col-md-3 col-sm-12">&nbsp;</label>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>