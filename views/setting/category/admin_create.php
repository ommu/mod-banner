<?php
/**
 * Banner Categories (banner-category)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\setting\CategoryController
 * @var $model ommu\banner\models\BannerCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 5 October 2017, 15:43 WIB
 * @modified date 24 January 2019, 13:06 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/setting/update']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="banner-category-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
