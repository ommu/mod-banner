<?php
/**
 * Banner Clicks (banner-clicks)
 * @var $this yii\web\View
 * @var $this ommu\banner\controllers\ClicksController
 * @var $model ommu\banner\models\search\BannerClicks
 * @var $form yii\widgets\ActiveForm
 *
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 6 October 2017, 13:06 WIB
 * @modified date 1 May 2018, 20:45 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
		<?php echo $form->field($model, 'banner_search');?>

		<?php echo $form->field($model, 'user_search');?>

		<?php echo $form->field($model, 'clicks');?>

		<?php echo $form->field($model, 'click_date')
			->input('date');?>

		<?php echo $form->field($model, 'click_ip');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>