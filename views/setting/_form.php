<?php
/**
 * Banner Settings (banner-setting)
 * @var $this yii\web\View
 * @var $this app\modules\banner\controllers\SettingController
 * @var $model app\modules\banner\models\BannerSetting
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 6 October 2017, 06:22 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\banner\models\BannerSetting;
use app\components\Utility;

$js = <<<JS
	$('input[name="BannerSetting[banner_validation]"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('input[name="BannerSetting[banner_resize]"][value="0"]').prop('checked', true);
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
]); ?>

<?php 
if($model->isNewRecord || (!$model->isNewRecord && $model->license == ''))
	$model->license = BannerSetting::getLicense();
echo $form->field($model, 'license', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('license'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
$permission = [
	1 => Yii::t('app', 'Yes, the public can view banner unless they are made private.'),
	0 => Yii::t('app', 'No, the public cannot view banner.'),
];
echo $form->field($model, 'permission', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12"><span class="small-px">'.Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.').'</span>{input}{error}</div>'])
	->radioList($permission, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('permission'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_description', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('meta_description'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_keyword', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('meta_keyword'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
$banner_validation = [
	1 => Yii::t('app', 'Yes, validation banner size before upload.'),
	0 => Yii::t('app', 'No, not validation banner size before upload.'),
];
echo $form->field($model, 'banner_validation', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->radioList($banner_validation, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('banner_validation'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
$banner_resize = [
	1 => Yii::t('app', 'Yes, resize banner after upload.'),
	0 => Yii::t('app', 'No, not resize banner after upload.'),
];
echo $form->field($model, 'banner_resize', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->radioList($banner_resize, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('banner_resize'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
if(!$model->getErrors()) {
	$banner_file_type = unserialize($model->banner_file_type);
	if(!empty($banner_file_type))
		$model->banner_file_type = Utility::formatFileType($banner_file_type, false);
}
echo $form->field($model, 'banner_file_type', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}<span class="small-px">'.Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "jpg, jpeg, png, bmp, gif"').'</span></div>'])
	->textInput()
	->label($model->getAttributeLabel('banner_file_type'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>