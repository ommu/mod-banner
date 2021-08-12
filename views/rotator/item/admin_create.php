<?php
/**
 * Link Rotator Items (link-rotator-item)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\rotator\ItemController
 * @var $model ommu\banner\models\LinkRotatorItem
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:58 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Link/WA Rotators'), 'url' => ['rotator/admin/index']];
$this->params['breadcrumbs'][] = ['label' => $model->category->title->message, 'url' => ['rotator/admin/view', 'id' => $model->cat_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item'), 'url' => ['rotator/item/manage', 'category' => $model->cat_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="link-rotator-item-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
