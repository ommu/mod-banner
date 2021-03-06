<?php
/**
 * ClickController
 * @var $this ommu\banner\controllers\history\ClickController
 * @var $model ommu\banner\models\BannerClickHistory
 *
 * ClickController implements the CRUD actions for BannerClickHistory model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 13:29 WIB
 * @modified date 24 January 2019, 17:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\controllers\history;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\banner\models\BannerClickHistory;
use ommu\banner\models\search\BannerClickHistory as BannerClickHistorySearch;

class ClickController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('click') || Yii::$app->request->get('id') || Yii::$app->request->get('banner')) {
            $this->subMenu = $this->module->params['banner_submenu'];
        }
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
        return [
            'access' => [
                'class' => AccessControl::className(),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all BannerClickHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new BannerClickHistorySearch();
        if (($banner = Yii::$app->request->get('banner')) != null) {
            $searchModel = new BannerClickHistorySearch(['bannerId' => $banner]);
        }
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($click = Yii::$app->request->get('click')) != null) {
            $click = \ommu\banner\models\BannerClicks::findOne($click);
			$this->subMenuParam = $click->banner_id;
		}
        if ($banner) {
			$this->subMenuParam = $banner;
			$banner = \ommu\banner\models\Banners::findOne($banner);
		}

		$this->view->title = Yii::t('app', 'Click Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'click' => $click,
			'banner' => $banner,
		]);
	}

	/**
	 * Displays a single BannerClickHistory model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);
		$this->subMenuParam = $model->click->banner_id;

		$this->view->title = Yii::t('app', 'Detail Click History: {click-id}', ['click-id' => $model->click->banner->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing BannerClickHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Banner click history success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'click' => $model->click_id]);
	}

	/**
	 * Finds the BannerClickHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return BannerClickHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = BannerClickHistory::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
