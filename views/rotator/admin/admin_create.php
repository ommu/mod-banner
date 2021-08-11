<?php
/**
 * Link Rotators (link-rotators)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\rotator\AdminController
 * @var $model ommu\banner\models\LinkRotators
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:56 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Link/WA Rotators'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="link-rotators-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
