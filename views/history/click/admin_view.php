<?php
/**
 * Banner Clicks (banner-clicks)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\history\ClickController
 * @var $model ommu\banner\models\BannerClicks
 *
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:06 WIB
 * @modified date 1 May 2018, 20:45 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner Clicks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->click_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'click_id',
		[
			'attribute' => 'banner_search',
			'value' => isset($model->banner) ? $model->banner->title : '-',
		],
		[
			'attribute' => 'user_search',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		'clicks',
		[
			'attribute' => 'click_date',
			'value' => Yii::$app->formatter->asDatetime($model->click_date, 'medium'),
		],
		'click_ip',
	],
]) ?>