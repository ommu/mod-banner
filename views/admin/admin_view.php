<?php
/**
 * Banners (banners)
 * @var $this yii\web\View
 * @var $this app\modules\banner\controllers\AdminController
 * @var $model app\modules\banner\models\Banners
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 08:14 WIB
 * @contact (+62)857-4115-5177
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\libraries\MenuContent;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->banner_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->banner_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<?php if($this->params['menu']['content']):
			echo MenuContent::widget(['items' => $this->params['menu']['content']]);
			endif;?>
			<ul class="nav navbar-right panel_toolbox">
				<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<?php echo DetailView::widget([
				'model' => $model,
				'options' => [
					'class'=>'table table-striped detail-view',
				],
				'attributes' => [
					'banner_id',
					[
						'attribute' => 'publish',
						'value' => $model->publish == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
					],
					[
						'attribute' => 'category_search',
						'value' => $model->category->title->message,
					],
					'title',
					[
						'attribute' => 'url',
						'value' => $model->url ? $model->url : '-',
					],
					[
						'attribute' => 'banner_filename',
						'value' => $model->banner_filename ? $model->banner_filename : '-',
					],
					[
                         'attribute' => 'banner_filename',
                         'format' => 'raw',
                         'value' => function ($model) {
                                    return Html::img(url::Base().'/public/banner/'.$model->banner_filename,
                                    ['width' => '400',
                                     'height' => '300']);
                                   },
                    ],	
					[
						'attribute' => 'banner_desc',
						'value' => $model->banner_desc ? $model->banner_desc : '-',
						'format'	=> 'html',
					],
					[
						'attribute' => 'published_date',
						'value' => !in_array($model->published_date, ['0000-00-00','1970-01-01']) ? Yii::$app->formatter->format($model->published_date, 'date') : '-',
					],
					[
						'attribute' => 'expired_date',
						'value' => !in_array($model->expired_date, ['0000-00-00','1970-01-01']) ? Yii::$app->formatter->format($model->expired_date, 'date') : '-',
					],
					[
						'attribute' => 'creation_date',
						'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-',
					],
					[
						'attribute' => 'creation_search',
						'value' => $model->creation_id ? $model->creation->displayname : '-',
					],
					[
						'attribute' => 'modified_date',
						'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
					],
					[
						'attribute' => 'modified_search',
						'value' => $model->modified_id ? $model->modified->displayname : '-',
					],
					[
						'attribute' => 'slug',
						'value' => $model->slug ? $model->slug : '-',
					],
				],
			]) ?>
		</div>
	</div>
</div>