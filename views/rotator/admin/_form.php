<?php
/**
 * Link Rotators (link-rotators)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\rotator\AdminController
 * @var $model ommu\banner\models\LinkRotators
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:56 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="link-rotators-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
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

<?php $rotatorType = $model::getRotatorType();
echo $form->field($model, 'rotator_type')
    ->dropDownList($rotatorType, ['prompt' => ''])
    ->label($model->getAttributeLabel('rotator_type')); ?>

<?php echo $form->field($model, 'name_i')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name_i')); ?>

<?php echo $form->field($model, 'desc_i')
	->textarea(['rows' => 6, 'cols' => 50, 'maxlength' => true])
	->label($model->getAttributeLabel('desc_i')); ?>

<?php echo $form->field($model, 'code')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('code')); ?>

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
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Rotator'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>