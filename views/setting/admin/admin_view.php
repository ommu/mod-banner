<?php
/**
 * Banner Settings (banner-setting)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\setting\AdminController
 * @var $model ommu\banner\models\BannerSetting
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 06:22 WIB
 * @modified date 30 April 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if ($breadcrumb) {
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/setting/update']];
	$this->params['breadcrumbs'][] = Yii::t('app', 'Banner');
}

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Reset'), 'url' => Url::to(['delete']), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to reset this setting?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="banner-setting-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'license',
		'value' => $model->license,
	],
	[
		'attribute' => 'permission',
		'value' => $model::getPermission($model->permission),
	],
	[
		'attribute' => 'meta_description',
		'value' => $model->meta_description ? $model->meta_description : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'meta_keyword',
		'value' => $model->meta_keyword ? $model->meta_keyword : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'banner_validation',
		'value' => $model::getBannerValidation($model->banner_validation),
		'visible' => !$small,
	],
	[
		'attribute' => 'banner_resize',
		'value' => $model::getBannerResize($model->banner_resize),
		'visible' => !$small,
	],
	[
		'attribute' => 'banner_file_type',
		'value' => $model->banner_file_type,
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), Url::to(['update']), [
			'class' => 'btn btn-primary',
		]),
		'format' => 'raw',
		'visible' => !$small,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>