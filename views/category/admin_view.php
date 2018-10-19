<?php
/**
 * Banner Categories (banner-category)
 * @var $this yii\web\View
 * @var $this ommu\banner\controllers\CategoryController
 * @var $model ommu\banner\models\BannerCategory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 5 October 2017, 15:43 WIB
 * @modified date 30 April 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\banner\models\BannerCategory;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['setting/index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->cat_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->cat_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'cat_id',
		[
			'attribute' => 'publish',
			'value' => $model->publish == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'name_i',
			'value' => isset($model->title) ? $model->title->message : '-',
		],
		[
			'attribute' => 'desc_i',
			'value' => isset($model->description) ? $model->description->message : '-',
		],
		'cat_code',
		[
			'attribute' => 'banner_size',
			'value' => BannerCategory::getSize($model->banner_size),
		],
		[
			'attribute' => 'banner_limit',
			'value' => $model->banner_limit,
		],
		[
			'attribute' => 'creation_date',
			'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-',
		],
		[
			'attribute' => 'creation_search',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-',
		],
		'slug',
	],
]) ?>