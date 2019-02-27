<?php
/**
 * Banner Settings (banner-setting)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\setting\AdminController
 * @var $model ommu\banner\models\BannerSetting
 * @var $form app\components\ActiveForm
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
use app\components\ActiveForm;
use ommu\banner\models\BannerSetting;

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
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $this->licenseCode();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = BannerSetting::getPermission();
echo $form->field($model, 'permission', ['template'=> '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission, ['class'=>'desc mt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<?php $bannerValidation = BannerSetting::getBannerValidation();
echo $form->field($model, 'banner_validation')
	->radioList($bannerValidation, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('banner_validation')); ?>

<?php $bannerResize = BannerSetting::getBannerResize();
echo $form->field($model, 'banner_resize')
	->radioList($bannerResize, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('banner_resize')); ?>

<?php echo $form->field($model, 'banner_file_type')
	->textInput()
	->label($model->getAttributeLabel('banner_file_type'))
	->hint(Yii::t('app', 'What file types do you want to allow for banner image (gif, jpg, jpeg, or png)? Separate file types with commas, i.e. jpg, jpeg, gif, png')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>