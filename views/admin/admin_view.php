<?php
/**
 * Banners (banners)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\AdminController
 * @var $model ommu\banner\models\Banners
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 08:14 WIB
 * @modified date 24 January 2019, 15:50 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use ommu\banner\models\Banners;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->banner_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->banner_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="banners-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'banner_id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
		],
		[
			'attribute' => 'cat_id',
			'value' => isset($model->category) ? $model->category->title->message : '-',
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
				return $model->banner_filename ? Html::img(join('/', [Url::Base(), $uploadPath, $model->banner_filename]), ['width' => '100%']).'<br/><br/>'.$model->banner_filename : '-';
			},
			'format' => 'html',
		],
		[
			'attribute' => 'banner_desc',
			'value' => $model->banner_desc ? $model->banner_desc : '-',
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
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creationDisplayname',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modifiedDisplayname',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
		[
			'attribute' => 'slug',
			'value' => $model->slug ? $model->slug : '-',
		],
		[
			'attribute' => 'clicks',
			'value' => Html::a($model->clicks ? $model->clicks : 0, ['history/click/manage', 'banner'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} clicks', ['count'=>$model->clicks])]),
			'format' => 'html',
		],
		[
			'attribute' => 'views',
			'value' => Html::a($model->views ? $model->views : 0, ['history/view/manage', 'banner'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} views', ['count'=>$model->views])]),
			'format' => 'html',
		],
	],
]) ?>

</div>