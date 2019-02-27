<?php
/**
 * Banners (banners)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\AdminController
 * @var $model ommu\banner\models\Banners
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 08:14 WIB
 * @modified date 24 January 2019, 15:50 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ActiveForm;
use ommu\banner\models\Banners;
use ommu\banner\models\BannerCategory;

$js = <<<JS
	$('.field-linked input[name="linked"]').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
			$('div.field-url').slideDown();
		} else {
			$('div.field-url').slideUp();
		}
	});
	$('.field-permanent input[name="permanent"]').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
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
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $category = BannerCategory::getCategory(1);
echo $form->field($model, 'cat_id')
	->dropDownList($category, ['prompt'=>''])
	->label($model->getAttributeLabel('cat_id')); ?>

<?php echo $form->field($model, 'title')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('title')); ?>

<?php 
if(!$model->getErrors()) {
	$model->linked = 0;
	if($model->isNewRecord || (!$model->isNewRecord && $model->url != '-'))
		$model->linked = 1;
}
echo $form->field($model, 'linked')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('linked')); ?>

<?php echo $form->field($model, 'url', ['options' => ['style' => $model->linked == 0 ? 'display: none' : '']])
	->textInput()
	->label($model->getAttributeLabel('url'))
	->hint('example: http://sudaryanto.id'); ?>

<?php $uploadPath = Banners::getUploadPath(false);
$bannerFilename = !$model->isNewRecord && $model->old_banner_filename != '' ? Html::img(join('/', [Url::Base(), $uploadPath, $model->old_banner_filename]), ['class'=>'mb-15', 'width'=>'100%']) : '';
echo $form->field($model, 'banner_filename', ['template'=> '{label}{beginWrapper}<div>'.$bannerFilename.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('banner_filename')); ?>

<?php echo $form->field($model, 'banner_desc')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('banner_desc')); ?>

<?php echo $form->field($model, 'published_date')
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('published_date')); ?>

<?php
if(!$model->getErrors()) {
	$model->permanent = 0;
	if(!$model->isNewRecord && in_array($model->expired_date, ['0000-00-00','1970-01-01','0002-12-02','-0001-11-30']))
		$model->permanent = 1;
}
echo $form->field($model, 'permanent')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('permanent')); ?>

<?php echo $form->field($model, 'expired_date', ['options' => ['style' => $model->permanent == 1 ? 'display: none' : '']])
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('expired_date')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>