<?php
/**
 * Banner Settings (banner-setting)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\setting\AdminController
 * @var $model ommu\banner\models\BannerSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 06:22 WIB
 * @modified date 23 January 2019, 16:06 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;

if ($breadcrumb) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/setting/update']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Update');
}

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Reset'), 'url' => Url::to(['delete']), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to reset this setting?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="banner-setting-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>