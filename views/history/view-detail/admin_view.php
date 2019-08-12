<?php
/**
 * Banner View Histories (banner-view-history)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\history\ViewDetailController
 * @var $model ommu\banner\models\BannerViewHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 May 2018, 11:10 WIB
 * @modified date 24 January 2019, 17:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->view->banner->title;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="banner-view-history-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'categoryId',
			'value' => isset($model->view->banner->category) ? $model->view->banner->category->title->message : '-',
		],
		[
			'attribute' => 'bannerTitle',
			'value' => isset($model->view->banner) ? $model->view->banner->title : '-',
		],
		[
			'attribute' => 'userDisplayname',
			'value' => isset($model->view->user) ? $model->view->user->displayname : '-',
		],
		[
			'attribute' => 'view_date',
			'value' => Yii::$app->formatter->asDatetime($model->view_date, 'medium'),
		],
		'view_ip',
	],
]) ?>

</div>