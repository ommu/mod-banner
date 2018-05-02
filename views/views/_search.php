<?php
/**
 * Banner Views (banner-views)
 * @var $this yii\web\View
 * @var $this app\modules\banner\controllers\ViewsController
 * @var $model app\modules\banner\models\search\BannerViews
 * @var $form yii\widgets\ActiveForm
 *
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 6 October 2017, 13:24 WIB
 * @modified date 1 May 2018, 20:44 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
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

		<?php echo $form->field($model, 'views');?>

		<?php echo $form->field($model, 'view_date')
			->input('date');?>

		<?php echo $form->field($model, 'view_ip');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
