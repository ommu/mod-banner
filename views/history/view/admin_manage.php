<?php
/**
 * Banner View Histories (banner-view-history)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\history\ViewController
 * @var $model ommu\banner\models\BannerViewHistory
 * @var $searchModel ommu\banner\models\search\BannerViewHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:24 WIB
 * @modified date 24 January 2019, 17:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use ommu\banner\models\BannerViews;

$this->params['breadcrumbs'][] = $this->title;

$viewUrl = $view ? Url::to(['o/view/manage', 'banner'=>$view->banner_id]) : Url::to(['o/view/manage']);
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Views'), 'url' => $viewUrl, 'icon' => 'table', 'htmlOptions' => ['class'=>'btn btn-success']],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="banner-view-history-manage">
<?php Pjax::begin(); ?>

<?php if($view != null) {
$model = $view;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'categoryId',
			'value' => function ($model) {
				$categoryId = isset($model->banner->category) ? $model->banner->category->title->message : '-';
				if($categoryId != '-')
					return Html::a($categoryId, ['setting/category/view', 'id'=>$model->banner->cat_id], ['title'=>$categoryId, 'class'=>'modal-btn']);
				return $categoryId;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'bannerTitle',
			'value' => function ($model) {
				$bannerTitle = isset($model->banner) ? $model->banner->title : '-';
				if($bannerTitle != '-')
					return Html::a($bannerTitle, ['admin/view', 'id'=>$model->banner_id], ['title'=>$bannerTitle, 'class'=>'modal-btn']);
				return $bannerTitle;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'userDisplayname',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		[
			'attribute' => 'view_date',
			'value' => Yii::$app->formatter->asDatetime($model->view_date, 'medium'),
		],
		'view_ip',
	],
]);
}?>

<?php if($banner != null) {
$model = $banner;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
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
		[
			'attribute' => 'title',
			'value' => function ($model) {
				if($model->title != '')
					return Html::a($model->title, ['admin/view', 'id'=>$model->banner_id], ['title'=>$model->title, 'class'=>'modal-btn']);
				return $model->title;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'url',
			'value' => $model->url ? $model->url : '-',
		],
		[
			'attribute' => 'banner_filename',
			'value' => function ($model) {
				$uploadPath = $model::getUploadPath(false);
				return $model->banner_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->banner_filename])), ['width' => '100%']).'<br/><br/>'.$model->banner_filename : '-';
			},
			'format' => 'html',
		],
		[
			'attribute' => 'published_date',
			'value' => Yii::$app->formatter->asDate($model->published_date, 'medium'),
		],
		[
			'attribute' => 'expired_date',
			'value' => Yii::$app->formatter->asDate($model->expired_date, 'medium'),
		],
	],
]);
}?>

<?php //echo $this->render('_search', ['model'=>$searchModel]); ?>

<?php echo $this->render('_option_form', ['model'=>$searchModel, 'gridColumns'=>$searchModel->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

<?php
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
		if($action == 'view')
			return Url::to(['view', 'id'=>$key]);
		if($action == 'update')
			return Url::to(['update', 'id'=>$key]);
		if($action == 'delete')
			return Url::to(['delete', 'id'=>$key]);
	},
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title'=>Yii::t('app', 'Detail'), 'class'=>'modal-btn']);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title'=>Yii::t('app', 'Update'), 'class'=>'modal-btn']);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>