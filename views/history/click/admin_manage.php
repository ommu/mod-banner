<?php
/**
 * Banner Click Histories (banner-click-history)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\history\ClickController
 * @var $model ommu\banner\models\BannerClickHistory
 * @var $searchModel ommu\banner\models\search\BannerClickHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:29 WIB
 * @modified date 24 January 2019, 17:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner'), 'url' => ['admin/index']];
if($click != null) {
	$this->params['breadcrumbs'][] = ['label' => $click->banner->title, 'url' => ['admin/view', 'id'=>$click->banner_id]];
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Click'), 'url' => ['o/click/manage', 'banner'=>$click->banner_id]];
} else
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Click'), 'url' => ['o/click/index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Histories');

$clickUrl = $click ? Url::to(['o/click/manage', 'banner'=>$click->banner_id]) : Url::to(['o/click/manage']);
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Clicks'), 'url' => $clickUrl, 'icon' => 'table', 'htmlOptions' => ['class'=>'btn btn-success']],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="banner-click-history-manage">
<?php Pjax::begin(); ?>

<?php if($click != null)
	echo $this->render('/o/click/admin_view', ['model'=>$click, 'small'=>true]); ?>

<?php if($banner != null)
	echo $this->render('/admin/admin_view', ['model'=>$banner, 'small'=>true]); ?>

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