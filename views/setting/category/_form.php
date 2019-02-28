<?php
/**
 * Banner Categories (banner-category)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\setting\CategoryController
 * @var $model ommu\banner\models\BannerCategory
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 5 October 2017, 15:43 WIB
 * @modified date 24 January 2019, 13:06 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
?>

<div class="banner-category-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('name_i')); ?>

<?php echo $form->field($model, 'desc_i')
	->textarea(['rows'=>6, 'cols'=>50, 'maxlength'=>true])
	->label($model->getAttributeLabel('desc_i')); ?>

<?php $banner_size_height = $form->field($model, 'banner_size[height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('banner_size[height]')])
	->label($model->getAttributeLabel('banner_size[height]')); ?>

<?php echo $form->field($model, 'banner_size[width]', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$banner_size_height.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6', 'error'=>'col-md-6 col-sm-9 col-xs-12 offset-sm-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('banner_size[width]')])
	->label($model->getAttributeLabel('banner_size')); ?>

<?php echo $form->field($model, 'banner_limit')
	->textInput(['type'=>'number', 'min'=>'1', 'maxlength'=>true])
	->label($model->getAttributeLabel('banner_limit')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>