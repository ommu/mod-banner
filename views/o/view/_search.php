<?php
/**
 * Banner Views (banner-views)
 * @var $this ViewController
 * @var $model BannerViews
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 January 2017, 20:54 WIB
 * @link https://github.com/ommu/ommu-banner
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
			<?php echo $model->getAttributeLabel('views'); ?>
			<?php echo $form->textField($model,'views'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_date'); ?>
			<?php //echo $form->textField($model,'view_date');
			$this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'view_date',
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
			<?php echo $model->getAttributeLabel('view_ip'); ?>
			<?php echo $form->textField($model,'view_ip'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
