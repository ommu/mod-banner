<?php
/**
 * Banner Click Histories (banner-click-history)
 * @var $this yii\web\View
 * @var $this app\modules\banner\controllers\ClickHistoryController
 * @var $model app\modules\banner\models\BannerClickHistory
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 13:29 WIB
 * @contact (+62)857-4115-5177
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\libraries\MenuContent;
use app\libraries\MenuOption;
use app\components\Utility;
use app\libraries\grid\GridView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Banner Clicks'), 'url' => Url::to(['click/index']), 'icon' => 'table'],
];
$this->params['menu']['option'] = [
	// ['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Options'), 'url' => 'javascript:void(0);'],
];
?>

<?php Pjax::begin(); ?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<?php if(Yii::$app->session->hasFlash('success'))
		echo Utility::flashMessage(Yii::$app->session->getFlash('success'));
	else if(Yii::$app->session->hasFlash('error'))
		echo Utility::flashMessage(Yii::$app->session->getFlash('error'), 'danger');?>

	<div class="x_panel">
		<div class="x_title">
			<?php if($this->params['menu']['content']):
			echo MenuContent::widget(['items' => $this->params['menu']['content']]);
			endif;?>
			<ul class="nav navbar-right panel_toolbox">
				<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				<?php if($this->params['menu']['option']):?>
				<li class="dropdown">
					<a href="#" title="<?php echo Yii::t('app', 'Options');?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
					<?php echo MenuOption::widget(['items' => $this->params['menu']['option']]);?>
				</li>
				<?php endif;?>
				<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<?php echo $this->description != '' ? "<p class=\"text-muted font-13 m-b-30\">$this->description</p>" : '';?>

			<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

			<?php echo $this->render('_option_form', ['model' => $searchModel, 'gridColumns' => GridView::getActiveDefaultColumns($columns), 'route' => $this->context->route]); ?>

			<?php 
			$columnData = $columns;
			array_push($columnData, [
				'class' => 'yii\grid\ActionColumn',
				'header' => Yii::t('app', 'Options'),
				'contentOptions' => [
					'class'=>'action-column',
				],
				'buttons' => [
					'view' => function ($url, $model, $key) {
						$url = Url::to(['view', 'id' => $model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'View Banner Click History')]);
					},
					'update' => function ($url, $model, $key) {
						$url = Url::to(['update', 'id' => $model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Banner Click History')]);
					},
					'delete' => function ($url, $model, $key) {
						$url = Url::to(['delete', 'id' => $model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
							'title' => Yii::t('app', 'Delete Banner Click History'),
							'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
							'data-method'  => 'post',
						]);
					},
				],
				'template' => '{delete}',
			]);
			
			echo GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'layout' => '<div class="row"><div class="col-sm-12">{items}</div></div><div class="row sum-page"><div class="col-sm-5">{summary}</div><div class="col-sm-7">{pager}</div></div>',
				'columns' => $columnData,
			]); ?>
		</div>
	</div>
</div>
<?php Pjax::end(); ?>