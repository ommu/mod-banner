<?php
/**
 * Link Rotator Items (link-rotator-item)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\rotator\ItemController
 * @var $model ommu\banner\models\search\LinkRotatorItem
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:58 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\banner\models\LinkRotators;
?>

<div class="link-rotator-item-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $category = LinkRotators::getRotator();
		echo $form->field($model, 'cat_id')
			->dropDownList($category, ['prompt' => '']);?>

		<?php echo $form->field($model, 'title');?>

		<?php echo $form->field($model, 'url');?>

		<?php echo $form->field($model, 'banner_desc');?>

		<?php echo $form->field($model, 'published_date')
			->input('date');?>

		<?php echo $form->field($model, 'expired_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'slug');?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>