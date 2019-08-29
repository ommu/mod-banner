<?php
/**
 * Banner Click Histories (banner-click-history)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\history\ClickController
 * @var $model ommu\banner\models\BannerClickHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 May 2018, 11:10 WIB
 * @modified date 24 January 2019, 17:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Click Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->click->banner->title;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="banner-click-history-view">

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
			$categoryId = isset($model->click->banner->category) ? $model->click->banner->category->title->message : '-';
			if($categoryId != '-')
				return Html::a($categoryId, ['setting/category/view', 'id'=>$model->click->banner->cat_id], ['title'=>$categoryId, 'class'=>'modal-btn']);
			return $categoryId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'bannerTitle',
		'value' => function ($model) {
			$bannerTitle = isset($model->click->banner) ? $model->click->banner->title : '-';
			if($bannerTitle != '-')
				return Html::a($bannerTitle, ['admin/view', 'id'=>$model->click->banner_id], ['title'=>$bannerTitle, 'class'=>'modal-btn']);
			return $bannerTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->click->user) ? $model->click->user->displayname : '-',
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
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>