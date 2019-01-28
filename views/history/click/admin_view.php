<?php
/**
 * Banner Clicks (banner-clicks)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\history\ClickController
 * @var $model ommu\banner\models\BannerClicks
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:06 WIB
 * @modified date 24 January 2019, 17:53 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clicks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->banner->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->click_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="banner-clicks-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'click_id',
		[
			'attribute' => 'categoryId',
			'value' => isset($model->banner->category) ? $model->banner->category->title->message : '-',
		],
		[
			'attribute' => 'bannerTitle',
			'value' => isset($model->banner) ? $model->banner->title : '-',
		],
		[
			'attribute' => 'userDisplayname',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		[
			'attribute' => 'click_date',
			'value' => Yii::$app->formatter->asDatetime($model->click_date, 'medium'),
		],
		'click_ip',
		[
			'attribute' => 'clicks',
			'value' => Html::a($model->clicks ? $model->clicks : 0, ['history/click-detail/manage', 'click'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} histories', ['count'=>$model->clicks])]),
			'format' => 'html',
		],
	],
]) ?>

</div>