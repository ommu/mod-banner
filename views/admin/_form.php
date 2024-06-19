<?php
/**
 * Banners (banners)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\AdminController
 * @var $model ommu\banner\models\Banners
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 08:14 WIB
 * @modified date 24 January 2019, 15:50 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use ommu\banner\models\Banners;
use ommu\banner\models\BannerCategory;
use ommu\flatpickr\Flatpickr;

$js = <<<JS
	$('.field-linked input[name="linked"]').on('change', function() {
		var id = $(this).prop('checked');
        if (id == true) {
			$('div.field-url').slideDown();
		} else {
			$('div.field-url').slideUp();
		}
	});
	$('.field-permanent input[name="permanent"]').on('change', function() {
		var id = $(this).prop('checked');
        if (id == true) {
			$('div.field-expired_date').slideUp();
		} else {
			$('div.field-expired_date').slideDown();
		}
	});
JS;
	$this->registerJs($js, \app\components\View::POS_READY);
?>

<div class="banners-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $category = BannerCategory::getCategory(1);
echo $form->field($model, 'cat_id')
	->dropDownList($category, ['prompt' => ''])
	->label($model->getAttributeLabel('cat_id')); ?>

<?php echo $form->field($model, 'title')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('title')); ?>

<?php 
if (!$model->getErrors()) {
	$model->linked = 0;
    if ($model->isNewRecord || (!$model->isNewRecord && $model->url != '-')) {
		$model->linked = 1;
    }
}
echo $form->field($model, 'linked')
	->checkbox()
	->label($model->getAttributeLabel('linked')); ?>

<?php echo $form->field($model, 'url', ['options' => ['style' => $model->linked == 0 ? 'display: none' : '']])
	->textInput()
	->label($model->getAttributeLabel('url'))
	->hint('example: http://ommu.id'); ?>

<?php $uploadPath = Banners::getUploadPath(false);
$bannerFilename = !$model->isNewRecord && $model->old_banner_filename != '' ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->old_banner_filename])), ['alt' => $model->old_banner_filename, 'class' => 'd-block border border-width-3 mb-4']).$model->old_banner_filename.'<hr/>' : '';
echo $form->field($model, 'banner_filename', ['template' => '{label}{beginWrapper}<div>'.$bannerFilename.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('banner_filename')); ?>

<?php echo $form->field($model, 'banner_desc')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('banner_desc')); ?>

<?php echo $form->field($model, 'published_date')
    ->widget(Flatpickr::className(), ['model' => $model, 'attribute' => 'published_date'])
	->label($model->getAttributeLabel('published_date')); ?>

<?php
if (!$model->getErrors()) {
	$model->permanent = 0;
    if (!$model->isNewRecord && Yii::$app->formatter->asDate($model->expired_date) == '-') {
		$model->permanent = 1;
    }
}
echo $form->field($model, 'permanent')
	->checkbox()
	->label($model->getAttributeLabel('permanent')); ?>

<?php echo $form->field($model, 'expired_date', ['options' => ['style' => $model->permanent == 1 ? 'display: none' : '']])
    ->widget(Flatpickr::className(), ['model' => $model, 'attribute' => 'expired_date'])
	->label($model->getAttributeLabel('expired_date')); ?>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
    $model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>