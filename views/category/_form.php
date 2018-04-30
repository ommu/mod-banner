<?php
/**
 * Banner Categories (banner-category)
 * @var $this yii\web\View
 * @var $this app\modules\banner\controllers\CategoryController
 * @var $model app\modules\banner\models\BannerCategory
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 5 October 2017, 15:43 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload'	  => ['/redactor/upload/image'],
	'fileUpload'	   => ['/redactor/upload/file'],
	'plugins'		  => ['clips', 'fontcolor','imagemanager']
];
?>

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
]); ?>

<?php echo $form->field($model, 'name_i', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'desc_i', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6,'maxlength' => true])
	->label($model->getAttributeLabel('desc_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'cat_code', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('cat_code'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="form-group field-bannercategory-banner_size-width field-bannercategory-banner_size-height required">
	<?php echo $form->field($model, 'banner_size', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('banner_size'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php 
		if(!$model->getErrors())
			$model->banner_size = unserialize($model->banner_size);
		echo $form->field($model, 'banner_size[width]', ['template' => '{input}{error}'])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Width')])
			->label($model->getAttributeLabel('banner_size'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php echo $form->field($model, 'banner_size[height]', ['template' => '{input}{error}'])
			->textInput(['type' => 'number', 'placeholder' => Yii::t('app', 'Height')])
			->label($model->getAttributeLabel('banner_size'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<?php echo $form->field($model, 'banner_limit', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['type' => 'number','maxlength' => true])
	->label($model->getAttributeLabel('banner_limit'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'publish', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>