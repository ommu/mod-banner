<?php
/**
 * Banner Click Histories (banner-click-history)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\history\ClickDetailController
 * @var $model ommu\banner\models\BannerClickHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 May 2018, 11:10 WIB
 * @modified date 24 January 2019, 17:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Click Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->click->banner->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="banner-click-history-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'categoryId',
			'value' => isset($model->click->banner->category) ? $model->click->banner->category->title->message : '-',
		],
		[
			'attribute' => 'bannerTitle',
			'value' => isset($model->click->banner) ? $model->click->banner->title : '-',
		],
		[
			'attribute' => 'userDisplayname',
			'value' => isset($model->click->user) ? $model->click->user->displayname : '-',
		],
		[
			'attribute' => 'click_date',
			'value' => Yii::$app->formatter->asDatetime($model->click_date, 'medium'),
		],
		'click_ip',
	],
]) ?>

</div>