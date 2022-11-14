<?php
/**
 * Banner Categories (banner-category)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\setting\CategoryController
 * @var $model ommu\banner\models\BannerCategory
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 5 October 2017, 15:43 WIB
 * @modified date 24 January 2019, 13:06 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\banner\models\BannerCategory;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/setting/update']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner'), 'url' => ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->title->message;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->cat_id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->cat_id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="banner-category-view">

<?php 
$attributes = [
	[
		'attribute' => 'cat_id',
		'value' => $model->cat_id,
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish, 'Enable,Disable'),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'name_i',
		'value' => $model->title->message,
	],
	[
		'attribute' => 'desc_i',
		'value' => $model->description->message,
	],
	[
		'attribute' => 'code',
		'value' => $model->code,
		'visible' => !$small,
	],
	[
		'attribute' => 'banner_size',
		'value' => BannerCategory::getSize($model->banner_size),
		'visible' => !$small,
	],
	[
		'attribute' => 'banner_limit',
		'value' => $model->banner_limit,
		'visible' => !$small,
	],
	[
		'attribute' => 'oPublish',
		'value' => function ($model) {
			$banners = $model->view->publish;
			return Html::a($banners, ['admin/manage', 'category' => $model->primaryKey, 'expired' => 'publish']);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'oPermanent',
		'value' => function ($model) {
			$permanent = $model->view->permanent;
			return Html::a($permanent, ['admin/manage', 'category' => $model->primaryKey, 'expired' => 'permanent']);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'oPending',
		'value' => function ($model) {
			$pending = $model->view->pending;
			return Html::a($pending, ['admin/manage', 'category' => $model->primaryKey, 'expired' => 'pending']);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'oExpired',
		'value' => function ($model) {
			$expired = $model->view->expired;
			return Html::a($expired, ['admin/manage', 'category' => $model->primaryKey, 'expired' => 'expired']);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'oUnpublish',
		'value' => function ($model) {
			$unpublish = $model->view->unpublish;
			return Html::a($unpublish, ['admin/manage', 'category' => $model->primaryKey, 'publish' => 0]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'oAll',
		'value' => function ($model) {
			$banners = $model->view->all;
			return Html::a($banners, ['admin/manage', 'category' => $model->primaryKey]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
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
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>