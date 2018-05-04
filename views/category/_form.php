<?php
/**
 * Banner Categories (banner-category)
 * @var $this yii\web\View
 * @var $this app\modules\banner\controllers\CategoryController
 * @var $model app\modules\banner\models\BannerCategory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 5 October 2017, 15:43 WIB
 * @modified date 30 April 2018, 13:27 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'name_i', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'desc_i', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6,'maxlength' => true])
	->label($model->getAttributeLabel('desc_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="form-group field-banner_size">
	<?php echo $form->field($model, 'banner_size[i]', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('banner_size[i]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-9 col-sm-9 col-xs-12 row">
		<?php echo $form->field($model, 'banner_size[width]', ['template' => '{input}{error}', 'options' => ['class' => 'col-md-6 col-sm-6 col-xs-12']])
			->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('banner_size[width]')])
			->label($model->getAttributeLabel('banner_size[width]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
			
		<?php echo $form->field($model, 'banner_size[height]', ['template' => '{input}{error}', 'options' => ['class' => 'col-md-6 col-sm-6 col-xs-12']])
			->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('banner_size[height]')])
			->label($model->getAttributeLabel('banner_size[height]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<?php echo $form->field($model, 'banner_limit', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type' => 'number', 'min' => '1','maxlength' => true])
	->label($model->getAttributeLabel('banner_limit'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'publish', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>