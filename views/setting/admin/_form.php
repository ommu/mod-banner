<?php
/**
 * Banner Settings (banner-setting)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\setting\AdminController
 * @var $model ommu\banner\models\BannerSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 06:22 WIB
 * @modified date 23 January 2019, 16:05 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;

$js = <<<JS
	$('.field-banner_validation input[name="banner_validation"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('.field-banner_resize input[name="banner_resize"][value="0"]').prop('checked', true);
		}
	});
	$('.field-banner_resize input[name="banner_resize"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('.field-banner_validation input[name="banner_validation"][value="0"]').prop('checked', true);
		}
	});
JS;
	$this->registerJs($js, \app\components\View::POS_READY);
?>

<div class="banner-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
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

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $model->licenseCode();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = $model::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<?php $bannerValidation = $model::getBannerValidation();
echo $form->field($model, 'banner_validation')
	->radioList($bannerValidation)
	->label($model->getAttributeLabel('banner_validation')); ?>

<?php $bannerResize = $model::getBannerResize();
echo $form->field($model, 'banner_resize')
	->radioList($bannerResize)
	->label($model->getAttributeLabel('banner_resize')); ?>

<?php echo $form->field($model, 'banner_file_type')
	->textInput()
	->label($model->getAttributeLabel('banner_file_type'))
	->hint(Yii::t('app', 'What file types do you want to allow for banner image (jpg, gif, or png)? Separate file types with commas, i.e. jpg, jpeg, bmp, gif, png')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>