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
			<?php echo $model->getAttributeLabel('click_date'); ?>
			<?php echo $this->filterDatepicker($model, 'click_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('click_ip'); ?>
			<?php echo $form->textField($model, 'click_ip', array('class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
