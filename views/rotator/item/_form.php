<?php
/**
 * Link Rotator Items (link-rotator-item)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\rotator\ItemController
 * @var $model ommu\banner\models\LinkRotatorItem
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:58 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use ommu\banner\models\LinkRotators;
use yii\helpers\ArrayHelper;
use ommu\flatpickr\Flatpickr;

$js = <<<JS
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

<div class="link-rotator-item-form">

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

<?php echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'cat_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput(); ?>

<?php echo $form->field($model, 'title')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('title')); ?>

<?php echo $form->field($model, 'banner_desc')
	->textarea(['rows' => 3, 'cols' => 50])
	->label($model->getAttributeLabel('banner_desc')); ?>

<?php echo $form->field($model, 'url')
	->textarea(['rows' => 2, 'cols' => 50])
	->label($model->getAttributeLabel('url')); ?>

<?php echo $form->field($model, 'published_date')
    ->widget(Flatpickr::className(), ['model' => $model, 'attribute' => 'published_date'])
	->label($model->getAttributeLabel('published_date')); ?>

<?php
if (!$model->getErrors()) {
	$model->permanent = 0;
    if (!$model->isNewRecord && Yii::$app->formatter->asDate($model->expired_date, 'php:Y-m-d') == '-') {
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

<?php $submitButtonOption = [];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Rotator Item'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>