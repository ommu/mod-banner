<?php
/**
 * Banner Clicks (banner-clicks)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\history\ClickController
 * @var $model ommu\banner\models\search\BannerClicks
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:06 WIB
 * @modified date 24 January 2019, 17:53 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\banner\models\BannerCategory;
?>

<div class="banner-clicks-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $category = BannerCategory::getCategory();
		echo $form->field($model, 'categoryId')
			->dropDownList($category, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'bannerTitle');?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php echo $form->field($model, 'clicks');?>

		<?php echo $form->field($model, 'click_date')
			->input('date');?>

		<?php echo $form->field($model, 'click_ip');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>