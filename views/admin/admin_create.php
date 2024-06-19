<?php
/**
 * Banners (banners)
 * @var $this app\components\View
 * @var $this ommu\banner\controllers\AdminController
 * @var $model ommu\banner\models\Banners
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 08:14 WIB
 * @modified date 24 January 2019, 15:50 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publication'), 'url' => ['/admin/page/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banner'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="banners-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
