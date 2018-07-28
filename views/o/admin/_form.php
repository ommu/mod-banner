<?php
/**
 * Banners (banners)
 * @var $this AdminController
 * @var $model Banners
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 23 January 2018, 07:07 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('#Banners_permanent_i').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
			$('div#expired-date').slideUp();
		} else {
			$('div#expired-date').slideDown();
		}
	});
	$('#Banners_linked_i').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
			$('div#url').slideDown();
		} else {
			$('div#url').slideUp();
		}
	});
EOP;
	$cs->registerScript('expired', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'banners-form',
	'enableAjaxValidation'=>true,
	/*
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	*/
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
	),
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<fieldset>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'cat_id', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php 
			$category = BannerCategory::getCategory(1);
			if($category != null)
				echo $form->dropDownList($model,'cat_id', $category, array('prompt'=>'', 'class'=>'form-control'));
			else
				echo $form->dropDownList($model,'cat_id', array('prompt'=>''), array('class'=>'form-control'));
			echo $form->error($model,'cat_id');?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'title', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->textField($model, 'title', array('maxlength'=>64, 'class'=>'form-control')); ?>
			<?php echo $form->error($model, 'title'); ?>
			<div class="small-px silent"><?php echo Yii::t('phrase', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vitae laoreet metus. Integer eros augue, viverra at lectus vel, dignissim sagittis erat. ');?></div>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'banner_desc', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->textArea($model, 'banner_desc', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
			<?php echo $form->error($model,'banner_desc'); ?>
		</div>
	</div>

	<?php 
	if(!$model->getErrors()) {
		$model->linked_i = 0;
		if($model->isNewRecord || (!$model->isNewRecord && $model->url != '-'))
			$model->linked_i = 1;
	}?>
	
	<div class="form-group row publish">
		<?php echo $form->labelEx($model,'linked_i', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->checkBox($model, 'linked_i', array('class'=>'form-control')); ?>
			<?php echo $form->labelEx($model, 'linked_i'); ?>
			<?php echo $form->error($model, 'linked_i'); ?>
		</div>
	</div>

	<div id="url" class="form-group row <?php echo $model->linked_i == 0 ? 'hide' : ''?>">
		<?php echo $form->labelEx($model, 'url', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->textArea($model, 'url', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
			<?php echo $form->error($model, 'url'); ?>
			<div class="small-px silent">example: https://github.com/ommu</div>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'banner_filename', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php 
			if(!$model->isNewRecord) {
				if(!$model->getErrors())
					$model->old_banner_filename_i = $model->banner_filename;
				echo $form->hiddenField($model,'old_banner_filename_i');
				if($model->old_banner_filename_i != '') {
					$bannerSize = unserialize($model->category->banner_size);
					$banner = Yii::app()->request->baseUrl.'/public/banner/'.$model->old_banner_filename_i;?>
					<img class="mb-15" src="<?php echo Utility::getTimThumb($banner, $bannerSize['width'], $bannerSize['height'], 3);?>" alt="">
			<?php }
			}?>
			<?php echo $form->fileField($model, 'banner_filename', array('class'=>'form-control')); ?>
			<?php echo $form->error($model, 'banner_filename'); ?>
			<span class="small-px">extensions are allowed: <?php echo Utility::formatFileType($banner_file_type, false);?></span>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model, 'published_date', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php $model->published_date = !$model->isNewRecord ? (!in_array($model->published_date, array('0000-00-00','1970-01-01','0002-12-02','-0001-11-30')) ? date('Y-m-d', strtotime($model->published_date)) : '') : '';
			/* $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'published_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'published_date', array('class'=>'form-control')); ?>
			<?php echo $form->error($model, 'published_date'); ?>
		</div>
	</div>

	<?php 
	if(!$model->getErrors()) {
		$model->permanent_i = 0;
		if($model->isNewRecord || (!$model->isNewRecord && in_array($model->expired_date, array('0000-00-00','1970-01-01','0002-12-02','-0001-11-30'))))
			$model->permanent_i = 1;
	}?>
	
	<div class="form-group row publish">
		<?php echo $form->labelEx($model,'permanent_i', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->checkBox($model, 'permanent_i', array('class'=>'form-control')); ?>
			<?php echo $form->labelEx($model, 'permanent_i'); ?>
			<?php echo $form->error($model, 'permanent_i'); ?>
		</div>
	</div>

	<div id="expired-date" class="form-group row <?php echo $model->permanent_i == 1 ? 'hide' : ''?>">
		<?php echo $form->labelEx($model, 'expired_date', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php
			if(!$model->getErrors())
				$model->expired_date = !$model->isNewRecord ? (!in_array($model->expired_date, array('0000-00-00','1970-01-01','0002-12-02','-0001-11-30')) ? date('Y-m-d', strtotime($model->expired_date)) : '') : '';
			/* $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'expired_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'expired_date', array('class'=>'form-control')); ?>
			<?php echo $form->error($model, 'expired_date'); ?>
		</div>
	</div>

	<div class="form-group row publish">
		<?php echo $form->labelEx($model, 'publish', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
		<div class="col-lg-6 col-md-9 col-sm-12">
			<?php echo $form->checkBox($model, 'publish', array('class'=>'form-control')); ?>
			<?php echo $form->labelEx($model, 'publish'); ?>
			<?php echo $form->error($model, 'publish'); ?>
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