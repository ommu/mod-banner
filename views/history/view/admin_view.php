<?php
/**
 * Banner View Histories (banner-view-history)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\history\ViewController
 * @var $model ommu\banner\models\BannerViewHistory
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 May 2018, 11:10 WIB
 * @modified date 24 January 2019, 17:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner'), 'url' => ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->view->banner->title, 'url' => ['admin/view', 'id' => $model->view->banner_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View'), 'url' => ['o/view/manage', 'banner' => $model->view->banner_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'History'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="banner-view-history-view">

<?php 
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'categoryId',
		'value' => function ($model) {
			$categoryId = isset($model->view->banner->category) ? $model->view->banner->category->title->message : '-';
            if ($categoryId != '-') {
                return Html::a($categoryId, ['setting/category/view', 'id' => $model->view->banner->cat_id], ['title' => $categoryId, 'class' => 'modal-btn']);
            }
			return $categoryId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'bannerTitle',
		'value' => function ($model) {
			$bannerTitle = isset($model->view->banner) ? $model->view->banner->title : '-';
            if ($bannerTitle != '-') {
                return Html::a($bannerTitle, ['admin/view', 'id' => $model->view->banner_id], ['title' => $bannerTitle, 'class' => 'modal-btn']);
            }
			return $bannerTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->view->user) ? $model->view->user->displayname : '-',
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
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>