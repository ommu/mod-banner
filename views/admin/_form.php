<?php
/**
 * Banners (banners)
 * @var $this yii\web\View
 * @var $this app\modules\banner\controllers\AdminController
 * @var $model app\modules\banner\models\Banners
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 08:14 WIB
 * @contact (+62)857-4115-5177
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\redactor\widgets\Redactor;
use app\modules\banner\models\BannerCategory;
use app\modules\banner\models\Banners;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload'	 => ['/redactor/upload/image'],
	'fileUpload'	   => ['/redactor/upload/file'],
	'plugins'		 => ['clips', 'fontcolor','imagemanager']
];
$js= <<<JS
	$('input[name="Banners[linked_i]"]').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
			$('div.field-banners-url').slideDown();
		} else {
			$('div.field-banners-url').slideUp();
		}
	});
	$('input[name="Banners[permanent_i]"]').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
			$('div.field-banners-expired_date').slideUp();
		} else {
			$('div.field-banners-expired_date').slideDown();
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
	'enableAjaxValidation'   => false,
	// disable validasi javascript di client/browser
	'enableClientValidation' => false,
	// disable hook javascript ".yii". jika ajax validasi aktif opsi ini harus true.
	'enableClientScript'	 => false,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php
$category = BannerCategory::getCategory(1);
echo $form->field($model,'cat_id', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->dropDownList($category)
	->label($model->getAttributeLabel('cat_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) ?>

<?php echo $form->field($model, 'title', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('title'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
if(!$model->getErrors()) {
	if($model->isNewRecord || (!$model->isNewRecord && $model->url != '-'))
		$model->linked_i = 0;

	$model->expired_date = '';
	$model->permanent_i  = 0;

	if(!$model->isNewRecord && !in_array(Yii::$app->formatter->asDate($model->expired_date, 'php:Y-m-d'), [
		'0000-00-00','1970-01-01','-0001-11-30'])) {
		$model->expired_date = Yii::$app->formatter->format($model->expired_date, 'date');
	}

	if($model->isNewRecord || (!$model->isNewRecord && in_array(Yii::$app->formatter->asDate(
		$model->expired_date, 'php:Y-m-d'), ['0000-00-00','1970-01-01','-0001-11-30']))) {
		$model->permanent_i = 1;
	}
}
echo $form->field($model, 'linked_i', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('linked_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'url', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}<div class="small-px silent">example: http://opensource.ommu.co</div></div>', 'options' => ['class' => 'form-group', 'style' => $model->linked_i == 0 ? 'display: none' : '']])
	->textInput()
	->label($model->getAttributeLabel('url'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="form-group field-banners-banner_filename required">
	<?php echo $form->field($model, 'banner_filename', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('banner_filename'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-6 col-sm-6 col-xs-12 checkbox">
		<?php if (!$model->isNewRecord) {
			if($model->old_banner_filename_i != '')
				echo Html::img(join('/', [Url::Base(), Banners::getBannerPath(false), $model->old_banner_filename_i]), ['class'=>'mb-15', 'width'=>'100%']);
		} ?>

		<?php echo $form->field($model, 'banner_filename', ['template' => '{input}{error}'])
			->fileInput() 
			->label($model->getAttributeLabel('banner_filename'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<?php echo $form->field($model, 'banner_desc', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('banner_desc'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
$model->published_date = !$model->isNewRecord ? (!in_array(Yii::$app->formatter->asDate($model->published_date, 'php:Y-m-d'), ['0000-00-00','1970-01-01','-0001-11-30']) ? Yii::$app->formatter->format($model->published_date, 'date') : '') : '';
echo $form->field($model, 'published_date', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['class' => 'form-control']])
	->label($model->getAttributeLabel('published_date'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php
echo $form->field($model, 'permanent_i', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('permanent_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php
echo $form->field($model, 'expired_date', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>', 'options' => ['class' => 'form-group', 'style' => $model->permanent_i == 1 ? 'display: none' : '']])
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['class' => 'form-control']])
	->label($model->getAttributeLabel('expired_date'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

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