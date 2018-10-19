<?php
/**
 * Banners (banners)
 * @var $this yii\web\View
 * @var $this ommu\banner\controllers\AdminController
 * @var $model ommu\banner\models\Banners
 * @var $form yii\widgets\ActiveForm
 *
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 6 October 2017, 08:14 WIB
 * @modified date 30 April 2018, 21:22 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use ommu\banner\models\Banners;
use ommu\banner\models\BannerCategory;

$js = <<<JS
	$('.field-linked_i input[name="linked_i"]').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
			$('div.field-url').slideDown();
		} else {
			$('div.field-url').slideUp();
		}
	});
	$('.field-permanent_i input[name="permanent_i"]').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
			$('div.field-expired_date').slideUp();
		} else {
			$('div.field-expired_date').slideDown();
		}
	});
JS;
	$this->registerJs($js, \yii\web\View::POS_READY);
?>

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php echo $form->errorSummary($model);?>

<?php
$cat_id = BannerCategory::getCategory(1);
echo $form->field($model, 'cat_id', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($cat_id, ['prompt'=>''])
	->label($model->getAttributeLabel('cat_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'title', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('title'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
if(!$model->getErrors()) {
	$model->linked_i = 0;
	if($model->isNewRecord || (!$model->isNewRecord && $model->url != '-'))
		$model->linked_i = 1;
}
echo $form->field($model, 'linked_i', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('linked_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'url', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}<span class="small-px">example: http://ecc.ft.ugm.ac.id</span></div>', 'options' => ['class' => 'form-group', 'style' => $model->linked_i == 0 ? 'display: none' : '']])
	->textInput()
	->label($model->getAttributeLabel('url'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="form-group field-banner_filename">
	<?php echo $form->field($model, 'banner_filename', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('banner_filename'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-9 col-sm-9 col-xs-12 checkbox">
		<?php echo !$model->isNewRecord && $model->old_banner_filename_i != '' ? Html::img(join('/', [Url::Base(), Banners::getUploadPath(false), $model->old_banner_filename_i]), ['class'=>'mb-15', 'width'=>'100%']) : '';?>
		<?php echo $form->field($model, 'banner_filename', ['template' => '{input}{error}'])
			->fileInput()
			->label($model->getAttributeLabel('banner_filename'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<?php echo $form->field($model, 'banner_desc', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('banner_desc'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'published_date', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('published_date'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php
if(!$model->getErrors()) {
	$model->permanent_i = 0;
	if(!$model->isNewRecord && in_array($model->expired_date, ['0000-00-00','1970-01-01','0002-12-02','-0001-11-30']))
		$model->permanent_i = 1;
}
echo $form->field($model, 'permanent_i', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('permanent_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'expired_date', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>', 'options' => ['class' => 'form-group', 'style' => $model->permanent_i == 1 ? 'display: none' : '']])
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('expired_date'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

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