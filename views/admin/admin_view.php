<?php
/**
 * Banners (banners)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\AdminController
 * @var $model ommu\banner\models\Banners
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 08:14 WIB
 * @modified date 13 February 2019, 05:27 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\banner\models\Banners;

if(!$small) {
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
} ?>

<div class="banners-view">

<?php 
$attributes = [
	[
		'attribute' => 'banner_id',
		'value' => $model->banner_id,
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'categoryName',
		'value' => function ($model) {
			$categoryName = isset($model->category) ? $model->category->title->message : '-';
			if($categoryName != '-')
				return Html::a($categoryName, ['setting/category/view', 'id'=>$model->cat_id], ['title'=>$categoryName, 'class'=>'modal-btn']);
			return $categoryName;
		},
		'format' => 'html',
	],
	'title',
	[
		'attribute' => 'url',
		'value' => $model->url ? $model->url : '-',
	],
	[
		'attribute' => 'banner_filename',
		'value' => function ($model) {
			$uploadPath = Banners::getUploadPath(false);
			return $model->banner_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->banner_filename])), ['alt'=>$model->banner_filename, 'class'=>'mb-3']).'<br/>'.$model->banner_filename : '-';
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'banner_desc',
		'value' => $model->banner_desc ? $model->banner_desc : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'published_date',
		'value' => Yii::$app->formatter->asDate($model->published_date, 'medium'),
	],
	[
		'attribute' => 'expired_date',
		'value' => Yii::$app->formatter->asDate($model->expired_date, 'medium'),
	],
	[
		'attribute' => 'clicks',
		'value' => function ($model) {
			$clicks = $model->getClicks(true);
			return Html::a($clicks, ['o/click/manage', 'banner'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} clicks', ['count'=>$clicks])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'views',
		'value' => function ($model) {
			$views = $model->getViews(true);
			return Html::a($views, ['o/view/manage', 'banner'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} views', ['count'=>$views])]);
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
	[
		'attribute' => 'slug',
		'value' => $model->slug ? $model->slug : '-',
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
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