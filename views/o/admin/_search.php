<?php
/**
 * Banners (banners)
 * @var $this AdminController
 * @var $model Banners
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
			<?php echo $model->getAttributeLabel('cat_id'); ?>
			<?php echo $form->textField($model,'cat_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('title'); ?>
			<?php echo $form->textField($model,'title'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('url'); ?>
			<?php echo $form->textArea($model,'url'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('banner_filename'); ?>
			<?php echo $form->textField($model,'banner_filename'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('banner_desc'); ?>
			<?php echo $form->textArea($model,'banner_desc'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('published_date'); ?>
			<?php //echo $form->textField($model,'published_date');
			$this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'published_date',
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
			<?php echo $model->getAttributeLabel('expired_date'); ?>
			<?php //echo $form->textField($model,'expired_date');
			$this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'expired_date',
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
			<?php echo $model->getAttributeLabel('creation_date'); ?>
			<?php //echo $form->textField($model,'creation_date');
			$this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
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
			$this->widget('application.libraries.core.components.system.CJuiDatePicker',array(
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


		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
