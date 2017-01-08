<?php
/**
 * Banner Click Details (banner-click-detail)
 * @var $this ClickdetailController
 * @var $model BannerClickDetail
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (ommu.co)
 * @created date 8 January 2017, 21:21 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Banner Click Details'=>array('manage'),
		$model->id,
	);
?>

<?php //begin.Messages ?>
<?php
if(Yii::app()->user->hasFlash('success'))
	echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
?>
<?php //end.Messages ?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
			'value'=>$model->id,
			//'value'=>$model->id != '' ? $model->id : '-',
		),
		array(
			'name'=>'click_id',
			'value'=>$model->click_id,
			//'value'=>$model->click_id != '' ? $model->click_id : '-',
		),
		array(
			'name'=>'click_date',
			'value'=>!in_array($model->click_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->click_date, true) : '-',
		),
		array(
			'name'=>'click_ip',
			'value'=>$model->click_ip,
			//'value'=>$model->click_ip != '' ? $model->click_ip : '-',
		),
	),
)); ?>

<div class="dialog-content">
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
