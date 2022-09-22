<?php
/**
 * Banner Views (banner-views)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\view\HistoryController
 * @var $model ommu\banner\models\search\BannerViews
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 13:24 WIB
 * @modified date 24 January 2019, 17:54 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\banner\models\BannerCategory;
?>

<div class="banner-views-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $category = BannerCategory::getCategory();
		echo $form->field($model, 'categoryId')
			->dropDownList($category, ['prompt' => '']);?>

		<?php echo $form->field($model, 'bannerTitle');?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php echo $form->field($model, 'views');?>

		<?php echo $form->field($model, 'view_date')
			->input('date');?>

		<?php echo $form->field($model, 'view_ip');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>