<?php
/**
 * Banner Views (banner-views)
 * @var $this yii\web\View
 * @var $this app\modules\banner\controllers\ViewController
 * @var $model app\modules\banner\models\search\BannerViews
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 13:24 WIB
 * @contact (+62)857-4115-5177
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
		<?= $form->field($model, 'view_id') ?>

		<?= $form->field($model, 'banner_id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'views') ?>

		<?= $form->field($model, 'view_date') ?>

		<?= $form->field($model, 'view_ip') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
