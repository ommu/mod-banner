<?php
/**
 * Banner Views (banner-views)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\o\ViewController
 * @var $model ommu\banner\models\BannerViews
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
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner'), 'url' => ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->banner->title, 'url' => ['admin/view', 'id'=>$model->banner_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View'), 'url' => ['manage', 'banner'=>$model->banner_id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->view_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="banner-views-view">

<?php 
$attributes = [
	[
		'attribute' => 'view_id',
		'value' => $model->view_id,
		'visible' => !$small,
	],
	[
		'attribute' => 'categoryId',
		'value' => function ($model) {
			$categoryId = isset($model->banner->category) ? $model->banner->category->title->message : '-';
            if ($categoryId != '-') {
                return Html::a($categoryId, ['setting/category/view', 'id'=>$model->banner->cat_id], ['title'=>$categoryId, 'class'=>'modal-btn']);
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
                return Html::a($bannerTitle, ['admin/view', 'id'=>$model->banner_id], ['title'=>$bannerTitle, 'class'=>'modal-btn']);
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
		'attribute' => 'views',
		'value' => function ($model) {
			$views = $model->views;
			return  Html::a($views, ['history/view/manage', 'view'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} histories', ['count'=>$views])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'view_date',
		'value' => Yii::$app->formatter->asDatetime($model->view_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'view_ip',
		'value' => $model->view_ip,
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