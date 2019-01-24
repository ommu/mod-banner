<?php
/**
 * Banner Categories (banner-category)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\setting\CategoryController
 * @var $model ommu\banner\models\BannerCategory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 5 October 2017, 15:43 WIB
 * @modified date 24 January 2019, 13:06 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use ommu\banner\models\BannerCategory;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title->message;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['setting/admin/index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->cat_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->cat_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="banner-category-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'cat_id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish, 'Enable,Disable'),
			'format' => 'raw',
		],
		[
			'attribute' => 'name_i',
			'value' => $model->name_i,
		],
		[
			'attribute' => 'desc_i',
			'value' => $model->desc_i,
		],
		'cat_code',
		[
			'attribute' => 'banner_size',
			'value' => BannerCategory::getSize($model->banner_size),
		],
		'banner_limit',
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creation_search',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
		'slug',
		[
			'attribute' => 'banners',
			'value' => Html::a($model->banners, ['admin/manage', 'category'=>$model->primaryKey, 'expired'=>'publish']),
			'format' => 'html',
		],
		[
			'attribute' => 'permanent',
			'value' => Html::a($model->permanent, ['admin/manage', 'category'=>$model->primaryKey, 'expired'=>'permanent']),
			'format' => 'html',
		],
		[
			'attribute' => 'pending',
			'value' => Html::a($model->pending, ['admin/manage', 'category'=>$model->primaryKey, 'expired'=>'pending']),
			'format' => 'html',
		],
		[
			'attribute' => 'expired',
			'value' => Html::a($model->expired, ['admin/manage', 'category'=>$model->primaryKey, 'expired'=>'expired']),
			'format' => 'html',
		],
	],
]) ?>

</div>