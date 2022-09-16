<?php
/**
 * Link Trees (link-tree)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\LinktreeController
 * @var $model ommu\banner\models\LinkTree
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 22:42 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\validators\UrlValidator;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Linktree'), 'url' => ['manage']];
    $this->params['breadcrumbs'][] = ['label' => $model->creation->username ? $model->creation->username : $model->creation->displayname, 'url' => ['manage', 'creation' => $model->creation_id]];
    $this->params['breadcrumbs'][] = $model->title;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->banner_id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->banner_id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="link-tree-view">

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
		'attribute' => 'title',
		'value' => $model->title ? $model->title : '-',
	],
	[
		'attribute' => 'url',
		'value' => function ($model) {
            $validator = new UrlValidator();
            if ($validator->validate($model->url) === true) {
                return Yii::$app->formatter->asUrl($model->url, ['target' => '_blank']);
            }
            return '-';
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'click',
		'value' => function ($model) {
			$clicks = $model->getClicks(true);
			return Html::a($clicks, ['click/admin/manage', 'banner' => $model->primaryKey, 'linktree' => true], ['title' => Yii::t('app', '{count} clicks', ['count' => $clicks])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'view',
		'value' => function ($model) {
			$views = $model->getViews(true);
			return Html::a($views, ['view/admin/manage', 'banner' => $model->primaryKey, 'linktree' => true], ['title' => Yii::t('app', '{count} views', ['count' => $views])]);
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
		'attribute' => 'slug',
		'value' => $model->slug ? $model->slug : '-',
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