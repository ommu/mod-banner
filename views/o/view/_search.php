<?php
/**
 * Banner Views (banner-views)
 * @var $this ViewController
 * @var $model BannerViews
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 January 2017, 20:54 WIB
 * @modified date 23 January 2018, 07:07 WIB
 * @link https://github.com/ommu/mod-banner
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
			<?php $category = BannerCategory::getCategory();
			if($category != null)
				echo $form->dropDownList($model, 'category_search', $category, array('prompt'=>'', 'class'=>'form-control'));
			else
				echo $form->dropDownList($model, 'category_search', array('prompt'=>'Select Category'), array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('banner_search'); ?>
			<?php echo $form->textField($model, 'banner_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_search'); ?>
			<?php echo $form->textField($model, 'user_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('views'); ?>
			<?php echo $form->textField($model, 'views', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_date'); ?>
			<?php /* $this->widget('application.libraries.core.components.system.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'view_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'yy-mm-dd',
				),
				'htmlOptions'=>array(
					'class' => 'form-control',
				 ),
			)); */
			echo $form->dateField($model, 'view_date', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('view_ip'); ?>
			<?php echo $form->textField($model, 'view_ip', array('class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
