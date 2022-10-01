<?php
/**
 * Link Rotator Items (link-rotator-item)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\rotator\ItemController
 * @var $model ommu\banner\models\LinkRotatorItem
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:58 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\validators\UrlValidator;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Link/WA Rotators'), 'url' => ['rotator/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->categoryTitle->message, 'url' => ['rotator/admin/view', 'id' => $model->cat_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item'), 'url' => ['rotator/item/manage', 'category' => $model->cat_id]];
    $this->params['breadcrumbs'][] = $model->title;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->banner_id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->banner_id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="link-rotator-item-view">

<?php
$attributes = [
	[
		'attribute' => 'banner_id',
		'value' => $model->banner_id ? $model->banner_id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'categoryName',
		'value' => function ($model) {
            $categoryName = isset($model->categoryTitle) ? $model->categoryTitle->message : '-';
            if ($categoryName != '-') {
                return Html::a($categoryName, ['rotator/admin/view', 'id' => $model->cat_id], ['title' => $categoryName, 'class' => 'modal-btn']);
            }
            return $categoryName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'title',
		'value' => $model->title ? $model->title : '-',
	],
	[
		'attribute' => 'banner_desc',
		'value' => $model->banner_desc ? $model->banner_desc : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'url',
		'value' => function ($model) {
            if ($model->category->rotator_type == 'url') {
                $validator = new UrlValidator();
                if ($validator->validate($model->url) === true) {
                    return Yii::$app->formatter->asUrl($model->url, ['target' => '_blank']);
                }
            } else {
                if ($model->url != '') {
                    return $model->url;
                }
            }
            return '-';
		},
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'published_date',
		'value' => Yii::$app->formatter->asDate($model->published_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'expired_date',
		'value' => Yii::$app->formatter->asDate($model->expired_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'click',
		'value' => function ($model) {
			$clicks = $model->grid->click;
			return Html::a($clicks, ['click/admin/manage', 'banner' => $model->primaryKey], ['title' => Yii::t('app', '{count} clicks', ['count' => $clicks])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'view',
		'value' => function ($model) {
			$views = $model->grid->view;
			return Html::a($views, ['view/admin/manage', 'banner' => $model->primaryKey], ['title' => Yii::t('app', '{count} views', ['count' => $views])]);
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
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm modal-btn']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>