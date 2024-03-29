<?php
/**
 * Banner Click Histories (banner-click-history)
 * @var $this ClickController
 * @var $model BannerClickHistory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 January 2017, 21:21 WIB
 * @modified date 23 January 2018, 07:09 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

	$this->breadcrumbs=array(
		'Banner Click Histories'=>array('manage'),
		Yii::t('phrase', 'Delete'),
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'banner-click-history-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="dialog-content">
		<?php echo Yii::t('phrase', 'Are you sure you want to delete this item?');?>
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton(Yii::t('phrase', 'Delete'), array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
	</div>
	
<?php $this->endWidget(); ?>
