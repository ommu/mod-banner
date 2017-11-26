<?php
/**
 * Banner Clicks (banner-clicks)
 * @var $this ClickController
 * @var $model BannerClicks
 * @var $form CActiveForm
 * version: 1.3.0
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 January 2017, 20:54 WIB
 * @link https://github.com/ommu/ommu-banner
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
			<?php echo $model->getAttributeLabel('category_search'); ?>
			<?php echo $form->textField($model,'category_search'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('banner_search'); ?>
			<?php echo $form->textField($model,'banner_search'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_search'); ?>
			<?php echo $form->textField($model,'user_search'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('clicks'); ?>
			<?php echo $form->textField($model,'clicks'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('click_date'); ?>
			<?php //echo $form->textField($model,'click_date');
			$this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'click_date',
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
			<?php echo $model->getAttributeLabel('click_ip'); ?>
			<?php echo $form->textField($model,'click_ip'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
