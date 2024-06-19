<?php
/**
 * Banner Settings (banner-setting)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\setting\AdminController
 * @var $model ommu\banner\models\BannerSetting
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 06:22 WIB
 * @modified date 30 April 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/setting/update']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Banner');
?>

<?php echo $this->renderWidget('/setting/category/admin_manage', [
	'contentMenu' => true,
	'searchModel' => $searchModel,
	'dataProvider' => $dataProvider,
	'columns' => $columns,
	'breadcrumb' => false,
]); ?>

<?php echo $this->renderWidget(!$model->isNewRecord ? 'admin_view' : 'admin_update', [
	'contentMenu' => true,
	'model' => $model,
	'breadcrumb' => false,
]); ?>