<?php
/**
 * Banner Clicks (banner-clicks)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\click\AdminController
 * @var $model ommu\banner\models\BannerClicks
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 13:06 WIB
 * @modified date 24 January 2019, 17:53 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner'), 'url' => ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->banner->title, 'url' => ['admin/view', 'id' => $model->banner_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Click'), 'url' => ['manage', 'banner' => $model->banner_id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->click_id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="banner-clicks-view">

<?php 
$attributes = [
	[
		'attribute' => 'click_id',
		'value' => $model->click_id,
		'visible' => !$small,
	],
	[
		'attribute' => 'categoryId',
		'value' => function ($model) {
			$categoryId = isset($model->categoryTitle) ? $model->categoryTitle->message : '-';
            if ($categoryId != '-') {
				return Html::a($categoryId, ['setting/category/view', 'id' => $model->banner->cat_id], ['title' => $categoryId, 'class' => 'modal-btn']);
            }
			return $categoryId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'bannerTitle',
		'value' => function ($model) {
			$bannerTitle = isset($model->banner) ? $model->banner->title : '-';
            if ($bannerTitle != '-') {
				return Html::a($bannerTitle, ['admin/view', 'id' => $model->banner_id], ['title' => $bannerTitle, 'class' => 'modal-btn']);
            }
			return $bannerTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'clicks',
		'value' => function ($model) {
			$clicks = $model->clicks;
			return  Html::a($clicks, ['click/history/manage', 'click' => $model->primaryKey], ['title' => Yii::t('app', '{count} histories', ['count' => $clicks])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'click_date',
		'value' => Yii::$app->formatter->asDatetime($model->click_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'click_ip',
		'value' => $model->click_ip,
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