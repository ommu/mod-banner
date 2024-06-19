<?php
/**
 * Link Trees (link-tree)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\LinktreeController
 * @var $model ommu\banner\models\LinkTree
 * @var $searchModel ommu\banner\models\search\LinkTree
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 22:42 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
if ($creation) {
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['manage']];
    $this->params['breadcrumbs'][] = $creation->username ? $creation->username : $creation->displayname;
} else {
    $this->params['breadcrumbs'][] = $this->title;
}

if ($creation != null) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Add Link'), 'url' => Url::to(['create', 'id' => $creation->user_id]), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success modal-btn']],
    ];
}
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="link-tree-manage">
<?php Pjax::begin(); ?>

<?php if ($category != null) {
	echo $this->render('/category/admin_view', ['model' => $category, 'small' => true]);
} ?>

<?php if ($creation != null) {
	echo $this->render('@users/views/member/admin_view', ['model' => $creation, 'small' => true]);
} ?>

<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo $this->render('_option_form', ['model' => $searchModel, 'gridColumns' => $searchModel->activeDefaultColumns($columns), 'route' => $this->context->route]); ?>

<?php
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
        if ($action == 'view') {
            if (!Yii::$app->request->get('creation')) {
                return Url::to(['manage', 'creation' => $model->creation_id]);
            }
            return Url::to(['view', 'id' => $key]);
        }
        if ($action == 'update') {
            return Url::to(['update', 'id' => $key]);
        }
        if ($action == 'delete') {
            return Url::to(['delete', 'id' => $key]);
        }
	},
	'buttons' => [
		'view' => function ($url, $model, $key) {
            $attr = ['title' => Yii::t('app', 'Detail Linktree')];
            if (Yii::$app->request->get('creation')) {
                $attr = ArrayHelper::merge($attr, ['class' => 'modal-btn']);
            } else {
                $attr = ArrayHelper::merge($attr, ['data-pjax' => 0]);
            }
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $attr);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Linktree'), 'class' => 'modal-btn']);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Linktree'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => $creation ? '{view} {update} {delete}' : '{view}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>