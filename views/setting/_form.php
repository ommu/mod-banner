<?php
/**
 * Banner Settings (banner-setting)
 * @var $this yii\web\View
 * @var $this ommu\banner\controllers\SettingController
 * @var $model ommu\banner\models\BannerSetting
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 06:22 WIB
 * @modified date 30 April 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\banner\models\BannerSetting;
use app\components\Utility;

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
	$this->registerJs($js, \yii\web\View::POS_READY);
?>

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->isNewRecord)
	$model->license = BannerSetting::getLicense();
echo $form->field($model, 'license', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12"><span class="small-px mb-10">'.Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'</span>{input}{error}<span class="small-px">'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX').'</span></div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('license'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
$permission = [
	1 => Yii::t('app', 'Yes, the public can view banner unless they are made private.'),
	0 => Yii::t('app', 'No, the public cannot view banner.'),
];
echo $form->field($model, 'permission', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.').'</span>{input}{error}</div>'])
	->radioList($permission, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('permission'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_description', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('meta_description'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_keyword', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('meta_keyword'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
$banner_validation = [
	1 => Yii::t('app', 'Yes, validation banner size before upload.'),
	0 => Yii::t('app', 'No, not validation banner size before upload.'),
];
echo $form->field($model, 'banner_validation', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->radioList($banner_validation, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('banner_validation'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
$banner_resize = [
	1 => Yii::t('app', 'Yes, resize banner after upload.'),
	0 => Yii::t('app', 'No, not resize banner after upload.'),
];
echo $form->field($model, 'banner_resize', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->radioList($banner_resize, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('banner_resize'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'banner_file_type', ['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}<span class="small-px">'.Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "jpg, jpeg, png, bmp, gif"').'</span></div>'])
	->textInput()
	->label($model->getAttributeLabel('banner_file_type'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>