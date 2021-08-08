<?php
/**
 * LinkTree (link-tree)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\linktree\AdminController
 * @var $model ommu\banner\models\LinkTree
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 22:42 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Linktree'), 'url' => ['manage']];
$this->params['breadcrumbs'][] = ['label' => $model->creation->username ? $model->creation->username : $model->creation->displayname, 'url' => ['manage', 'creation' => $model->creation_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="link-tree-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
